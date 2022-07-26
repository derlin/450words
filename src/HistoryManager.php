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
    private $page, $per_page, $offset, $count, $pages_count;

    public function __construct($date, $page = 0, $per_page = 40, $ellipsis = 70)
    {
        $this->date = $date;
        $this->page = intval($page);
        $this->per_page = intval($per_page);

        $mysqli = dbConnect("words");

        // get count
        $stmt = $mysqli->query("SELECT COUNT(*) FROM `words` where userid = '" . $_SESSION['uid'] . "'");
        $this->count = $stmt->fetch_row()[0];
        $this->pages_count = ceil($this->count / $this->per_page);
        $stmt->close();

        // fix out-of-range pages
        if($page > 0 && $page >= $this->pages_count) {
            $this->page = $this->pages_count - 1;
        }

        // get data
        $this->offset = $this->page * $this->per_page;
        $stmt = $mysqli->prepare('select day, word from words where userid = ? and day <= ? order by day desc limit ? offset ?;');
        $stmt->bind_param('ssdd', $_SESSION['uid'], //
            F::link($date), $per_page, $this->offset);

        $stmt->bind_result($day, $text);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $text = sanitize_entry($text, false);
            $this->existing_entries[$day] = strlen($text) > $ellipsis ? substr($text, 0, $ellipsis) . "..." : $text;
        }
        $stmt->close();
        $mysqli->close();
    }

    public function get_count()
    {
        return $this->count;
    }

    public function get_display_count()
    {
        return count($this->existing_entries);
    }

    public function get_display_description()
    {
        return 'Entries ' . $this->offset . '-' . ($this->offset + $this->get_display_count()) . ' out of ' . $this->get_count();
    }


    public function print_pagination()
    {
        $nb = 7;        // number of items to display
        $nb_side = $nb - 2;  // consecutive items on the side (2 = ellipsis + last item)
        $nb_middle = intdiv($nb - $nb_side, 2); // items on left and right if in the middle of the range ($nb - 1(current page) - 4(extremity + ellispis))/2

        $n_pages = $this->pages_count;
        $page = $this->page;

        if ($n_pages == 0) return; // no need for pagination if no data...
        ?>
        <nav aria-label="pagination" xmlns="http://www.w3.org/1999/html">
        <ul class="pagination"><?php
            if ($n_pages <= $nb) {
                // we can display all
                $navigation_pages = range(0, $n_pages - 1);
            } else if ($page < $nb_side - 1) { // minus one since we start at index 0
                // the page is at the beginning, no ellipsis on the left
                $navigation_pages = array_merge(range(0, $nb_side - 1), array('...', $n_pages - 1));
            } else if ($page > $n_pages - $nb_side) {
                // the page is at the end, no ellipsis on the right
                $navigation_pages = array_merge(array(0, '...'), range($n_pages - $nb_side, $n_pages - 1));
            } else {
                // the page in the middle, ellispsis on both sides
                $navigation_pages = array_merge(array(0, '...'), range($page - $nb_middle, $page + $nb_middle), array('...', $n_pages - 1));
            }

            // now, construct the pagination, adding < and > at each extremity.
            ?>
            <li class="<?= $page == 0 ? 'disabled' : '' ?>"><a href="<?= $this->create_pagination_link($page - 1) ?>">&lt;</a></li>
            <?php
            foreach ($navigation_pages as $i) {
                $class = $i === '...' ? "disabled" : ($i == $page ? "active" : ""); 
                ?>
                <li class="<?= $class ?>"><a href="<?= $this->create_pagination_link($i) ?>"><?= $i ?></a></li>
                <?php
            }
            ?>
            <li class="<?= $page == $n_pages - 1 ? 'disabled' : '' ?>"><a href="<?= $this->create_pagination_link($page + 1) ?>">&gt;</a></li>
        
        </ul>
        </nav>
        <?php
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

    private function create_pagination_link($page) {
        return sprintf("?date=%s&page=%d&per_page=%d", F::link($this->date), $page, $this->per_page);
    }


}