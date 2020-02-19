<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.5

into query function:

        $query_start_time = microtime();
        $GLOBALS['START_TIME_DB_QUERY'][] = $query_start_time;



 */


class request_profiler
{

    var $data = array();
    var $memory_data = array();
    var $total_db_query_count = 0;
    var $total_parse_time = 0;
    var $total_db_query_time = 0;
    var $current_id = 0;
    var $track_memory = false;
    var $show_memory = false;

    function request_profiler()
    {

    }

    function start()
    {
        if (!defined('DISPLAY_REQUEST_PROFILER') || !DISPLAY_REQUEST_PROFILER) {
            return false;
        }
        $this->current_memory = memory_get_usage();
        $this->current_id++;
        $this->data[$this->current_id]['start_time'] = microtime(true);
        if (isset($GLOBALS['START_TIME_DB_QUERY'])) {
            $this->data[$this->current_id]['start_dbquery_count'] = count($GLOBALS['START_TIME_DB_QUERY']);
        } else {
            $this->data[$this->current_id]['start_dbquery_count'] = 0;
        }
    }

    function stop($name)
    {
        if (!$this->check_memory()) {
            echo "*** Memory limit reached at $name***";
            print_r($this->memory_data);
            exit();
        }
        if (!DISPLAY_REQUEST_PROFILER) {
            return false;
        }
        if ($this->show_memory) {
            echo "<br>memory @ $name: " . memory_get_usage() / (1024 * 1014) . 'M';
        }

        if ($this->track_memory) {
            $this->memory_data[$name] += memory_get_usage() - $this->current_memory;
        }

        $this->data[$this->current_id]['name'] = $name;
        $this->data[$this->current_id]['stop_time'] = microtime(true);
        if (isset($GLOBALS['START_TIME_DB_QUERY'])) {
            $this->data[$this->current_id]['stop_dbquery_count'] = count($GLOBALS['START_TIME_DB_QUERY']);
        } else {
            $this->data[$this->current_id]['stop_dbquery_count'] = 0;
        }
    }

    function restart($name)
    {
        $this->stop($name);
        $this->start();
    }

    function consolidate()
    {
        $data_array_keys = array_keys($this->data);

        for ($i = 0; $i < count($data_array_keys); $i++) {
            $time_start = explode(' ', $this->data[$data_array_keys[$i]]['start_time']);
            $time_end = explode(' ', $this->data[$data_array_keys[$i]]['stop_time']);
            $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 6);

            $db_query_count = $this->data[$data_array_keys[$i]]['stop_dbquery_count'] -
                $this->data[$data_array_keys[$i]]['start_dbquery_count'];

            $db_query_time = 0;

            if ($db_query_count > 0) {
                $db_start_time_index = $this->data[$data_array_keys[$i]]['start_dbquery_count'];
                $db_end_time_index = $this->data[$data_array_keys[$i]]['stop_dbquery_count'];

                for ($j = $db_start_time_index; $j < $db_end_time_index; $j++) {
                    $db_time_start = explode(' ', $GLOBALS['START_TIME_DB_QUERY'][$j]);
                    $db_time_end = explode(' ', $GLOBALS['END_TIME_DB_QUERY'][$j]);
                    $db_query_time += number_format(($db_time_end[1] + $db_time_end[0] - ($db_time_start[1] + $db_time_start[0])), 6);
                }
            }
            $this->data[$data_array_keys[$i]]['db_query_count'] = $db_query_count;
            $this->data[$data_array_keys[$i]]['parse_time'] = $parse_time;
            $this->data[$data_array_keys[$i]]['db_query_time'] = $db_query_time;
            $this->total_db_query_count += $db_query_count;
            $this->total_parse_time += $parse_time;
            $this->total_db_query_time += $db_query_time;
        }
    }

    function check_memory($limit_percentage = 5)
    {
        $memory_limit_raw = ini_get('memory_limit'); // e.g. 512M

        if (stristr($memory_limit_raw, 'M')) {
            $memory_limit = (int)$memory_limit_raw;
            $memory_limit *= 1024 * 1024;
        } else {
            $memory_limit = $memory_limit_raw;
        }

        $memory_usage = memory_get_usage(true); // in bytes

        if ($memory_usage / $memory_limit > (100 - $limit_percentage) / 100) {
            // reaching memory limit
            echo "<hr>usage: $memory_usage<br>";
            echo "limit: $memory_limit_raw<hr>";
            return false;
        } else {
            // ok
            return true;
        }
    }

    function memory_warning() {
        // todo
        // formated output of warning
    }


    function output()
    {

        $this->consolidate();

        $data_array_keys = array_keys($this->data);

        echo '<table class="requestProfiler" width="100%"><tr>
        <td>#</td><td>Block</td><td align="right">allover time [ms]</td><td align="right">%</td><td align="right">db queries</td><td align="right">db time [ms]</td><td align="right">%</td><td>memory</td></tr>';

        for ($i = 0; $i < count($data_array_keys); $i++) {
            echo '<tr>';
            echo '<td class="requestProfiler">' . $data_array_keys[$i] . '</td>';
            echo '<td class="requestProfiler">' . $this->data[$data_array_keys[$i]]['name'] . '</td>';
            echo '<td class="requestProfiler" align="right">' . number_format($this->data[$data_array_keys[$i]]['parse_time'] * 1000, 2) . '</td>';
            echo '<td class="requestProfiler" align="right">' . number_format($this->data[$data_array_keys[$i]]['parse_time'] / $this->total_parse_time * 100, 2) . '</td>';
            echo '<td class="requestProfiler" align="right">' . $this->data[$data_array_keys[$i]]['db_query_count'] . '</td>';
            echo '<td class="requestProfiler" align="right">' . number_format($this->data[$data_array_keys[$i]]['db_query_time'] * 1000, 2) . '</td>';
            echo '<td class="requestProfiler" align="right">' . number_format($this->data[$data_array_keys[$i]]['db_query_time'] / $this->total_db_query_time * 100, 2) . '</td>';

            if ($this->show_memory) {
                echo '<td class="requestProfiler" align="right">' . $this->data[$data_array_keys[$i]]['memory'] . '</td>';
            }
            echo '</tr>';
        }
        echo '<tr>';
        echo '<td class="requestProfiler" align="right">&nbsp;</td><td class="requestProfiler">&nbsp;</td>';
        echo '<td class="requestProfiler" align="right">' . number_format($this->total_parse_time * 1000, 2) . '</td><td class="requestProfiler">&nbsp;</td>';
        echo '<td class="requestProfiler" align="right">' . $this->total_db_query_count . '</td>';
        echo '<td class="requestProfiler" align="right">' . number_format($this->total_db_query_time * 1000, 2) . '</td><td class="requestProfiler">&nbsp;</td>';
        if ($this->show_memory) {
            echo '<td class="requestProfiler" align="right"></td>';
        }
        echo '</tr>';
        echo '<table><br /><br />';
        if ($this->track_memory) {
            print_r($this->memory_data);
        }
    }
}

?>