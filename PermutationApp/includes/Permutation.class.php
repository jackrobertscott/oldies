<?php 
/**
* This class accepts a word as an arguement.
* It can then return an array of all the permutations of the word. 
*/
class Permutation
{
	private $letters = array();
	private $perms = array();
	private $time;

	/**
	* Fill (array) $letters with charecters of given string
	* Fill the $zerms array with permutations of the string
	*
	* @param (String) $word = any string to be used.
	*/
	function __construct($word = '')
	{
		$this->letters = str_split(strtolower(trim($word)));
		$pre = microtime();
		$this->permute(0, count($this->letters));
		$post = microtime();
		$this->time = $post - $pre;
	}

	/**
	* @return (array) $perms = String permutation array
	*/
	public function getPerms()
	{
		return $this->perms;
	}

	/**
	* @return (int) number of permutations made
	*/
	public function getNumPerms()
	{
		return count($this->perms);
	}

	/**
	* @return (int) time taken to execute
	*/
	public function getTime()
	{
		return $this->time;
	}

	/**
	* Swap charecters within array to build permutations
	*
	* @param (int) $p = the current position in string 
	* @param (int) $l = the length of the string
	*/
	private function permute($p, $l)
	{
		if($p == $l)
		{
			$this->perms[] = implode('', $this->letters);
		}else{
			for($i = $p; $i < $l; $i++)
			{
				$this->swap($p, $i, $l);
				$this->permute($p + 1, $l);
				$this->swap($p, $i, $l); //reverse initial swap
			}
		}
	}

	/**
	* Swap array values
	*
	* @param (int) $p = position one to swap
	* @param (int) $i = position two to swap
	* @param (int) $l = the length of the string
	*/
	private function swap($p, $i, $l) 
	{
		if($p < 0 || $p >= $l)
			return; //protect from trying to access values outside array
		$temp = $this->letters[$p];
		$this->letters[$p] = $this->letters[$i];
		$this->letters[$i] = $temp;
	}
}
?>