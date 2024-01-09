<?php

namespace App\Libraries;

class SumToText
{
	public function sum_to_text($skaicius)
	{
		$Sum = sprintf('%01.2f', $skaicius);
		list($p1, $p2) = explode('.', $Sum);
		$SumZodziais = $this->getSumZodziais($p1).' '.$this->getLitai($p1).' '.$p2.' ct.';
		return $SumZodziais;
	}

	public function getTrys($skaicius)
	{
		$vienetai = array ('', 'vienas', 'du', 'trys', 'keturi', 'penki', 'šeši', 'septyni', 'aštuoni', 'devyni');
		$niolikai = array ('', 'vienuolika', 'dvylika', 'trylika', 'keturiolika', 'penkiolika', 'šešiolika', 'septyniolika', 'aštuoniolika', 'devyniolika');
		$desimtys = array ('', 'dešimt', 'dvidešimt', 'trisdešimt', 'keturiasdešimt', 'penkiasdešimt', 'šešiasdešimt', 'septyniasdešimt', 'aštuoniasdešimt', 'devyniasdešimt');
  		
		$skaicius = sprintf("%03d", $skaicius);
		$simtai = ($skaicius[0] == 1)?"šimtas":"šimtai";
		if ($skaicius[0] == 0) $simtai = "";

		$du = substr($skaicius, 1);
		if  (($du > 10) && ($du < 20))
			return $this->getSumZodziais($skaicius[0]."00")." ".$niolikai[$du[1]];
		else
			return $vienetai[$skaicius[0]]." ".$simtai." ".$desimtys[$skaicius[1]]." ".$vienetai[$skaicius[2]];
	}
  
	public function getSumZodziais($skaicius){
		$zodis = array(
			array("", "", ""),
			array("tūkstančių", "tūkstantis", "tūkstančiai"),
			array("milijonų", "milijonas", "milijonai"),
			array("milijardų", "milijardas", "milijardai"),
			array("bilijonų", "bilijonas", "bilijonai"));

			$return = "";
			if ($skaicius == 0)
				return "";

			settype($skaicius, "string");
			$size = strlen($skaicius);
			$skaicius = str_pad($skaicius, ceil($size/3)*3, "0", STR_PAD_LEFT);

			for ($ii=0; $ii<$size; $ii+=3)
			{
				$tmp = substr($skaicius, 0-$ii-3, 3);
				$return = $this->getTrys($tmp)." ".$zodis[$ii/3][($tmp[2]>1)?2:$tmp[2]]." ".$return;
			}

				return $return;
	}

	public function getCentus($skaicius)
	{
		$centai = explode('.', $skaicius); return $centai[1]; 
	}

	public function getLitai($number)
	{
		if ($number == 0)
			return 'eurų';

		$last = substr($number, -1);
		$du = substr($number, -2, 2);

		if (($du > 10) && ($du < 20))
			return 'eurų';
		else
		{
			if ($last == 0)
				return 'eurų';
			elseif ($last == 1)
				return 'euras';
			else
				return 'eurai';
		}
	}
}