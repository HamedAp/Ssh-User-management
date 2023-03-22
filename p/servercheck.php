<?php 
include('header.php'); 
include('menu.php');
	$start_time = microtime(TRUE);
	$operating_system = PHP_OS_FAMILY;
		$load = sys_getloadavg();
		$cpuload = $load[0];
		$free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); });
		$mem = array_merge($mem); 
		$memtotal = round($mem[1] / 1000000,2);
		$memused = round($mem[2] / 1000000,2);
		$memfree = round($mem[3] / 1000000,2);
		$memshared = round($mem[4] / 1000000,2);
		$memcached = round($mem[5] / 1000000,2);
		$memavailable = round($mem[6] / 1000000,2);
		$connections = `netstat -ntu | grep :80 | grep ESTABLISHED | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
		$totalconnections = `netstat -ntu | grep :80 | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
	$memusage = round(($memavailable/$memtotal)*100);
	$phpload = round(memory_get_usage() / 1000000,2);
	$diskfree = round(disk_free_space(".") / 1000000000);
	$disktotal = round(disk_total_space(".") / 1000000000);
	$diskused = round($disktotal - $diskfree);
	$diskusage = round($diskused/$disktotal*100);
	if ($memusage > 85 || $cpuload > 85 || $diskusage > 85) {
		$trafficlight = 'red';
	} elseif ($memusage > 50 || $cpuload > 50 || $diskusage > 50) {
		$trafficlight = 'orange';
	} else {
		$trafficlight = '#2F2';
	}
	$end_time = microtime(TRUE);
	$time_taken = $end_time - $start_time;
	$total_time = round($time_taken,4);
?>

 <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">اطلاعات سرور : </div>
						<div class="table-responsive" >
						
						<div style="direction:ltr;padding-left:30px">
						
		
		<p><span class="description">🌡️ RAM Total:</span> <span class="result"><?php echo $memtotal; ?> GB</span></p>
		<p><span class="description">🌡️ RAM Used:</span> <span class="result"><?php echo $memused; ?> GB</span></p>
		<p><span class="description">🌡️ RAM Available:</span> <span class="result"><?php echo $memavailable; ?> GB</span></p>
		<hr>
		<p><span class="description">💽 Hard Disk Free:</span> <span class="result"><?php echo $diskfree; ?> GB</span></p>
		<p><span class="description">💽 Hard Disk Used:</span> <span class="result"><?php echo $diskused; ?> GB</span></p>
		<p><span class="description">💽 Hard Disk Total:</span> <span class="result"><?php echo $disktotal; ?> GB</span></p>
		<hr>
		<div id="details">
			<p><span class="description">📟 Server Name: </span> <span class="result"><?php echo $_SERVER['SERVER_NAME']; ?></span></p>
			<p><span class="description">💻 Server Addr: </span> <span class="result"><?php echo $_SERVER['SERVER_ADDR']; ?></span></p>
			<p><span class="description">🌀 PHP Version: </span> <span class="result"><?php echo phpversion(); ?></span></p>
			<p><span class="description">🏋️ PHP Load: </span> <span class="result"><?php echo $phpload; ?> GB</span></p>
			<p><span class="description">⏱️ Load Time: </span> <span class="result"><?php echo $total_time; ?> sec</span></p>
		</div>
						
						</div>
						
						
                        
						</div>
                    </div>
                </div>
            </div>
			</div>
            <?php include('footer.php'); ?>




		
	