<?
/*
Server names consist of an alphabetic host type (e.g. "apibox") concatenated with
the server number, with server numbers allocated as before (so "apibox1", "apibox2",
etc. are valid hostnames).
Write a name tracking class with two operations, allocate(host_type) and
deallocate(hostname). The former should reserve and return the next available
hostname, while the latter should release that hostname back into the pool.

>> tracker = Tracker.new()
>> tracker.allocate("apibox")
"apibox1"
>> tracker.allocate("apibox")
"apibox2"
>> tracker.deallocate("apibox1")
nil
>> tracker.allocate("apibox")
"apibox1"
>> tracker.allocate("sitebox")
"sitebox1"
*/

Class Tracker{

	public $sites = array();
	
	function allocate($site){
		$lastVal = 1; 
	
		if ( !isset($this->sites[$site]) || !isset($this->sites[$site][1]) ){
			$this->sites[$site][1] = 1;
			return $site.$lastVal;
		}
		
		$sites=$this->sites[$site];
		$siteSort = sort($sites, SORT_NUMERIC ) != 1 ? sort($sites, SORT_NUMERIC ): $sites;
		for($i=0; $i<count($siteSort); $i++){		

			if ( !in_array( $siteSort[$i]+1, $siteSort) ){
				$lastVal = $siteSort[$i]+1;
				$this->sites[$site][$lastVal] = $lastVal;
				return $site.$lastVal;
			}	
		}
	}	

	function deallocate($site){
		$dom = preg_split('/[0-9]/', $site);
		$subDom = preg_split('/[a-zA-Z]/', $site);
		$subDom = $subDom[count($subDom)-1];
		//$siteD = $this->sites[$dom[0]];
		if ( count($this->sites[$dom[0]]) > 1 ){
			unset($this->sites[$dom[0]][$subDom]);
			
		}else{
			unset($this->sites[$dom[0]]);
			
		}
		return null;
		
	}	
}

$t = new Tracker;
assert($t -> allocate("apibox") == "apibox1");
assert($t -> allocate("apibox") == "apibox2");
assert($t -> deallocate("apibox1") == null);
assert($t -> allocate("apibox") == "apibox1");
assert($t -> allocate("apibox") == "apibox3");
assert($t -> deallocate("apibox1") == null);
assert($t -> deallocate("apibox1") == null);
assert($t -> allocate("apibox") == "apibox1");
assert($t -> allocate("sandbox") == "sandbox1");
?>
