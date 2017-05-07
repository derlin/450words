<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 14/06/15
 * Time: 16:25
 */
include 'MyDate.php';
include_once 'utils/utils.php';

class HistoryManager
{

    private $date;
    private $existing_entries = array();
    private $page, $per_page, $count, $pages_count;

    public function __construct($date, $page = 0, $per_page = 40, $ellipsis = 70)
    {
        $this->date = $date;
        $this->page = intval($page);
        $this->per_page = intval($per_page);

        $mysqli = dbConnect("words");

        // get count
        $stmt = $mysqli->query("SELECT COUNT(*) FROM `words`");
        $this->count = $stmt->fetch_row()[0];
        $this->pages_count = ceil($this->count / $this->per_page);
        $stmt->close();

        // fix out-of-range pages
        if($page >= $this->pages_count) {
            $this->page = $this->pages_count - 1;
        }

        // get data
        $offset = $this->page * $this->per_page;
        $stmt = $mysqli->prepare('select day, word from words where userid = ? and day <= ? order by day desc limit ? offset ?;');
        $stmt->bind_param('ssdd', $_SESSION['uid'], //
            F::link($date), $per_page, $offset);

        $stmt->bind_result($day, $text);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $text = sanitize_entry($text, false);
            $this->existing_entries[$day] = strlen($text) > $ellipsis ? substr($text, 0, $ellipsis) . "..." : $text;
        }
        $stmt->close();
        $mysqli->close();

        echo count($this->existing_entries);
    }

    public function get_count()
    {
        return $this->count;
    }

    public function get_display_count()
    {
        return count($this->existing_entries);
    }


    public function print_pagination()
    {
        $nb = 4;

        ?>
        <nav aria-label="pagination" xmlns="http://www.w3.org/1999/html">
        <ul class="pagination"><?php
            if ($this->pages_count <= 2 * $nb) {
                $navigation_pages = range(0, $this->pages_count-1);
            } else {
                $navigation_pages = array_merge(range(0, $nb-1), array('...'), range($this->pages_count - $nb, $this->pages_count-1));
            }

            foreach ($navigation_pages as $i) {
                $classes = $i === '...' || $i === $this->page ? "active disabled" : '';
                $link = sprintf("?date=%s&page=%d&per_page=%d", F::link($this->date), $i, $this->per_page);
                ?>
                <li class="<?= $classes ?>"><a href="<?= $link ?>"><?= $i ?></a></li>
                <?php
            }

            ?></ul></nav><?php
    }

    public function print_entries()
    {
        ?>
        <ul>
            <?php
            foreach ($this->existing_entries as $day => $text) {
                ?>
                <li>
                    <a href="/?date=<?= $day ?>" target="_blank" class="day"><?= $day ?></a>
                    <span class="word"><?= $text; ?></span>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }

    private function count_words($string)
    {
        $st = str_replace('\n', ' ', strip_tags($string));
        $count = count(preg_split('/\s+/', trim($st)));

        return $count;
    }


}