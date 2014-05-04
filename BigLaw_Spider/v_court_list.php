<?php

  //法院列表
  /*
  <OPTION value="TPC 司法院－刑事補償" selected>司法院－刑事補償</OPTION>
  <OPTION value="TPU 司法院－訴願決定">司法院－訴願決定</OPTION>
  <OPTION value="TPJ 司法院職務法庭">司法院職務法庭</OPTION>
  <OPTION value="TPS 最高法院">最高法院</OPTION>
  <OPTION value="TPA 最高行政法院">最高行政法院</OPTION>
  <OPTION value="TPP 公務員懲戒委員會">公務員懲戒委員會</OPTION>
  <OPTION value="TPH 臺灣高等法院">臺灣高等法院</OPTION>
  <OPTION value="TPH 臺灣高等法院－訴願決定">臺灣高等法院－訴願決定</OPTION>
  <OPTION value="TPB 臺北高等行政法院">臺北高等行政法院</OPTION>
  <OPTION value="TCB 臺中高等行政法院">臺中高等行政法院</OPTION>
  <OPTION value="KSB 高雄高等行政法院">高雄高等行政法院</OPTION>
  <OPTION value="IPC 智慧財產法院">智慧財產法院</OPTION>
  <OPTION value="TCH 臺灣高等法院 臺中分院">臺灣高等法院 臺中分院</OPTION>
  <OPTION value="TNH 臺灣高等法院 臺南分院">臺灣高等法院 臺南分院</OPTION>
  <OPTION value="KSH 臺灣高等法院 高雄分院">臺灣高等法院 高雄分院</OPTION>
  <OPTION value="HLH 臺灣高等法院 花蓮分院">臺灣高等法院 花蓮分院</OPTION>
  <OPTION value="TPD 臺灣臺北地方法院">臺灣臺北地方法院</OPTION>
  <OPTION value="SLD 臺灣士林地方法院">臺灣士林地方法院</OPTION>
  <OPTION value="PCD 臺灣新北地方法院">臺灣新北地方法院</OPTION>
  <OPTION value="ILD 臺灣宜蘭地方法院">臺灣宜蘭地方法院</OPTION>
  <OPTION value="KLD 臺灣基隆地方法院">臺灣基隆地方法院</OPTION>
  <OPTION value="TYD 臺灣桃園地方法院">臺灣桃園地方法院</OPTION>
  <OPTION value="SCD 臺灣新竹地方法院">臺灣新竹地方法院</OPTION>
  <OPTION value="MLD 臺灣苗栗地方法院">臺灣苗栗地方法院</OPTION>
  <OPTION value="TCD 臺灣臺中地方法院">臺灣臺中地方法院</OPTION>
  <OPTION value="CHD 臺灣彰化地方法院">臺灣彰化地方法院</OPTION>
  <OPTION value="NTD 臺灣南投地方法院">臺灣南投地方法院</OPTION>
  <OPTION value="ULD 臺灣雲林地方法院">臺灣雲林地方法院</OPTION>
  <OPTION value="CYD 臺灣嘉義地方法院">臺灣嘉義地方法院</OPTION>
  <OPTION value="TND 臺灣臺南地方法院">臺灣臺南地方法院</OPTION>
  <OPTION value="KSD 臺灣高雄地方法院">臺灣高雄地方法院</OPTION>
  <OPTION value="HLD 臺灣花蓮地方法院">臺灣花蓮地方法院</OPTION>
  <OPTION value="TTD 臺灣臺東地方法院">臺灣臺東地方法院</OPTION>
  <OPTION value="PTD 臺灣屏東地方法院">臺灣屏東地方法院</OPTION>
  <OPTION value="PHD 臺灣澎湖地方法院">臺灣澎湖地方法院</OPTION>
  <OPTION value="KMH 福建高等法院金門分院">福建高等法院金門分院</OPTION>
  <OPTION value="KMD 福建金門地方法院">福建金門地方法院</OPTION>
  <OPTION value="LCD 福建連江地方法院">福建連江地方法院</OPTION>
  <OPTION value="KSY 臺灣高雄少年及家事法院">臺灣高雄少年及家事法院</OPTION>
  */
  
  //法院級別，S:最高院 H:高院 D:地院 A:最高行政 B:高等行政 P:公懲會
	/*
	 * 法院級別 -> 可選裁判類別
	 * 名稱含訴願決定 ->A
	 * SHD		-> MVA
	 * YC		-> M
	 * BA		-> A
	 * P		-> P
	 * J		-> V
	*/

  $M_v_court_list = array(
    0 => 'TPC 司法院－刑事補償',
    1 => 'TPS 最高法院',
    2 => 'TPH 臺灣高等法院',
    3 => 'IPC 智慧財產法院',
    4 => 'TCH 臺灣高等法院 臺中分院',
    5 => 'TNH 臺灣高等法院 臺南分院',
    6 => 'KSH 臺灣高等法院 高雄分院',
    7 => 'HLH 臺灣高等法院 花蓮分院',
	8 => 'TPD 臺灣臺北地方法院',
	9 => 'SLD 臺灣士林地方法院',
   10 => 'PCD 臺灣新北地方法院',
   11 => 'ILD 臺灣宜蘭地方法院',
   12 => 'KLD 臺灣基隆地方法院',
   13 => 'TYD 臺灣桃園地方法院',
   14 => 'SCD 臺灣新竹地方法院',
   15 => 'MLD 臺灣苗栗地方法院',
   16 => 'TCD 臺灣臺中地方法院',
   17 => 'CHD 臺灣彰化地方法院',
   18 => 'NTD 臺灣南投地方法院',
   19 => 'ULD 臺灣雲林地方法院',
   20 => 'CYD 臺灣嘉義地方法院',
   21 => 'TND 臺灣臺南地方法院',
   22 => 'KSD 臺灣高雄地方法院',
   23 => 'HLD 臺灣花蓮地方法院',
   24 => 'TTD 臺灣臺東地方法院',
   25 => 'PTD 臺灣屏東地方法院',
   26 => 'PHD 臺灣澎湖地方法院',
   27 => 'KMH 福建高等法院金門分院',
   28 => 'KMD 福建金門地方法院',
   29 => 'LCD 福建連江地方法院'
  );
?>