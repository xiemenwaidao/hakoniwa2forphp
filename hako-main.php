<?php
/*******************************************************************

  ���돔���Q for PHP

  
  $Id: hako-main.php,v 1.20 2004/12/14 12:24:56 Watson Exp $

*******************************************************************/

require 'jcode.phps';
require 'config.php';
require 'hako-html.php';
require 'hako-turn.php';
$init = new Init;

define("READ_LINE", 4096);
$THIS_FILE =  $init->baseDir . "/hako-main.php";
$BACK_TO_TOP = "<A HREF=\"{$THIS_FILE}?\">{$init->tagBig_}�g�b�v�֖߂�{$init->_tagBig}</A>";
$ISLAND_TURN; // �^�[����

$PRODUCT_VERSION = '20060612';

//--------------------------------------------------------------------
class Hako extends HakoIO {
  var $islandList;	// �����X�g
  var $targetList;	// �^�[�Q�b�g�̓����X�g
  var $defaultTarget;	// �ڕW�⑫�p�^�[�Q�b�g
  
  function readIslands(&$cgi) {
    global $init;
    
    $m = $this->readIslandsFile($cgi);
    $this->islandList = $this->getIslandList($cgi->dataSet['defaultID']);
    if($init->targetIsland == 1) {
      // �ڕW�̓� ���L�̓����I�����ꂽ���X�g
      $this->targetList = $this->islandList;
    } else {
      // ���ʂ�TOP�̓����I�����ꂽ��Ԃ̃��X�g
      $this->targetList = $this->getIslandList($cgi->dataSet['defaultTarget']);
    }
    return $m;
  }

  //---------------------------------------------------
  // �����X�g����
  //---------------------------------------------------
  function getIslandList($select = 0) {
    $list = "";
    for($i = 0; $i < $this->islandNumber; $i++) {
      $name = $this->islands[$i]['name'];
      $id   = $this->islands[$i]['id'];

      // �U���ڕW�����炩���ߎ����̓��ɂ���
      if(empty($this->defaultTarget)) {$this->defaultTarget = $id;}

      if($id == $select) {
        $s = "selected";
      } else {
        $s = "";
      }
      $list .= "<option value=\"$id\" $s>{$name}��</option>\n";
    }
    return $list;
  }
  //---------------------------------------------------
  // �܂Ɋւ��郊�X�g�𐶐�
  //---------------------------------------------------
  function getPrizeList($prize) {
    global $init;
    list($flags, $monsters, $turns) = split(",", $prize, 3);

    $turns = split(",", $turns);
    $prizeList = "";
    // �^�[���t
    $max = -1;
    $nameList = "";
    if($turns[0] != "") {
      for($k = 0; $k < count($turns) - 1; $k++) {
        $nameList .= "[{$turns[$k]}] ";
        $max = $k;
      }
    }
    if($max != -1) {
      $prizeList .= "<img src=\"prize0.gif\" alt=\"$nameList\" width=\"16\" height=\"16\"> ";
    }
    // ��
    $f = 1;
    for($k = 1; $k < count($init->prizeName); $k++) {
      if($flags & $f) {
        $prizeList .= "<img src=\"prize{$k}.gif\" alt=\"{$init->prizeName[$k]}\" width=\"16\" height=\"16\"> ";
      }
      $f = $f << 1;
    }
    // �|�������b���X�g
    $f = 1;
    $max = -1;
    $nameList = "";
    for($k = 0; $k < $init->monsterNumber; $k++) {
      if($monsters & $f) {
        $nameList .= "[{$init->monsterName[$k]}] ";
        $max = $k;
      }
      $f = $f << 1;
    }
    if($max != -1) {
      $prizeList .= "<img src=\"{$init->monsterImage[$max]}\" alt=\"{$nameList}\" width=\"16\" height=\"16\"> ";
    }
    return $prizeList;
  }
  //------------------------------------------------------------------

  //---------------------------------------------------
  // �n�`�Ɋւ���f�[�^����
  //---------------------------------------------------
  function landString($l, $lv, $x, $y, $mode, $comStr) {
    global $init;
    $point = "({$x},{$y})";
    $naviExp = "''";

    if($x < $init->islandSize / 2)
      $naviPos = 0;
    else
      $naviPos = 1;

    switch($l) {
    case $init->landSea:
      switch($lv) {
      case 1:
        // ��
        $image = 'land14.gif';
        $naviTitle = '��';
        break;
      default:
        // �C
        $image = 'land0.gif';
        $naviTitle = '�C';
      }
      break;
    case $init->landWaste:
      // �r�n
      if($lv == 1) {
        $image = 'land13.gif'; // ���e�_
      } else {
        $image = 'land1.gif';
      }
      $naviTitle = '�r�n';
      break;
    case $init->landPlains:
      // ���n
      $image = 'land2.gif';
      $naviTitle = '���n';
      break;
    case $init->landForest:
      // �X
      if($mode == 1) {
        $image = 'land6.gif';
        $naviText= "{$lv}{$init->unitTree}";
      } else {
        // �ό��҂̏ꍇ�͖؂̖{���B��
        $image = 'land6.gif';
      }
      $naviTitle = '�X';
      break;
    case $init->landTown:
      // ��
      $p; $n;
      if($lv < 30) {
        $p = 3;
        $naviTitle = '��';
      } else if($lv < 100) {
        $p = 4;
        $naviTitle = '��';
      } else {
        $p = 5;
        $naviTitle = '�s�s';
      }
      $image = "land{$p}.gif";
      $naviText = "{$lv}{$init->unitPop}";
      break;
    case $init->landFarm:
      // �_��
      $image = 'land7.gif';
      $naviTitle = '�_��';
      $naviText = "{$lv}0{$init->unitPop}�K��";
      break;
    case $init->landFactory:
      // �H��
      $image = 'land8.gif';
      $naviTitle = '�H��';
      $naviText = "{$lv}0{$init->unitPop}�K��";
      break;
    case $init->landBase:
      if($mode == 0 || $mode == 2) {
        // �ό��҂̏ꍇ�͐X�̂ӂ�
        $image = 'land6.gif';
        $naviTitle = '�X';
      } else {
        // �~�T�C����n
        $level = Util::expToLevel($l, $lv);
        $image = 'land9.gif';
        $naviTitle = '�~�T�C����n';
        $naviText = "���x�� {$level} / �o���l {$lv}";
      }
      break;
    case $init->landSbase:
      // �C���n
      if($mode == 0 || $mode == 2) {
        // �ό��҂̏ꍇ�͊C�̂ӂ�
        $image = 'land0.gif';
        $naviTitle = '�C';
      } else {
        $level = Util::expToLevel($l, $lv);
        $image = 'land12.gif';
        $naviTitle = '�C���n';
        $naviText = "���x�� {$level} / �o���l {$lv}";
      }
      break;
    case $init->landDefence:
      // �h�q�{��
      $image = 'land10.gif';
      $naviTitle = '�h�q�{��';
      break;
    case $init->landHaribote:
      // �n���{�e
      $image = 'land10.gif';
      if($mode == 0 || $mode == 2) {
        // �ό��҂̏ꍇ�͖h�q�{�݂̂ӂ�
        $naviTitle = '�h�q�{��';
      } else {
        $naviTitle = '�n���{�e';
      }
      break;
    case $init->landOil:
      // �C����c
      $image = 'land16.gif';
      $naviTitle = '�C����c';
      break;
    case $init->landMountain:
      // �R
      if($lv > 0) {
        $image = 'land15.gif';
        $naviTitle = '�̌@��';
        $naviText = "{$lv}0{$init->unitPop}�K��";
      } else {
        $image = 'land11.gif';
        $naviTitle = '�R';
      }
      break;
    case $init->landMonument:
      // �L�O��
      $image = $init->monumentImage[$lv];
      $naviTitle = '�L�O��';
      $naviText = $init->monumentName[$lv];
      break;
    case $init->landMonster:
      // ���b
      $monsSpec = Util::monsterSpec($lv);
      $special = $init->monsterSpecial[$monsSpec['kind']];
      $image = $init->monsterImage[$monsSpec['kind']];
      $naviTitle = '���b';

      // �d����?
      if((($special == 3) && (($this->islandTurn % 2) == 1)) ||
         (($special == 4) && (($this->islandTurn % 2) == 0))) {
        // �d����
        $image = $init->monsterImage2[$monsSpec['kind']];
      }
      $naviText = "���b{$monsSpec['name']}(�̗�{$monsSpec['hp']})";
    }

    if($mode == 1 || $mode == 2) {
      print "<a href=\"javascript: void(0);\" onclick=\"ps($x,$y)\" onkeypress=\"ps($x,$y)\">";
      $naviText = "{$comStr}\\n{$naviText}";
    }
    print "<img src=\"{$image}\" width=\"32\" height=\"32\" alt=\"{$point} {$naviTitle} {$comStr}\" onMouseOver=\"Navi({$naviPos},'{$image}', '{$naviTitle}', '{$point}', '{$naviText}', {$naviExp});\" onMouseOut=\"NaviClose(); return false\">";

    // ���W�ݒ��
    if($mode == 1 || $mode == 2)
      print "</a>";
  }
}
//--------------------------------------------------------------------
class HakoIO {
  var $islandTurn;	// �^�[����
  var $islandLastTime;	// �ŏI�X�V����
  var $islandNumber;	// ���̑���
  var $islandNextID;	// ���Ɋ��蓖�Ă铇ID
  var $islands;		// �S���̏����i�[
  var $idToNumber;
  var $idToName;

  //---------------------------------------------------
  // �S���f�[�^��ǂݍ���
  // 'mode'���ς��\��������̂�$cgi���Q�ƂŎ󂯎��
  //---------------------------------------------------
  function readIslandsFile(&$cgi) {
    global $init;
    $num = $cgi->dataSet['ISLANDID'];

    $fileName = "{$init->dirName}/hakojima.dat";
    if(!is_file($fileName)) {
      return false;
    }
    $fp = fopen($fileName, "r");

    $this->islandTurn     = chop(fgets($fp, READ_LINE));
    $this->islandLastTime = chop(fgets($fp, READ_LINE));
    $this->islandNumber   = chop(fgets($fp, READ_LINE));
    $this->islandNextID   = chop(fgets($fp, READ_LINE));

    $GLOBALS['ISLAND_TURN'] = $this->islandTurn;
      
    // �^�[����������
    $now = time();
    if((DEBUG && (strcmp($cgi->dataSet['mode'], 'debugTurn') == 0)) ||
       (($now - $this->islandLastTime) >= $init->unitTime)) {
      $cgi->mode = $data['mode'] = 'turn';
      $num = -1;
    }
    for($i = 0; $i < $this->islandNumber; $i++) {
      $this->islands[$i] = $this->readIsland($fp, $num);
      $this->idToNumber[$this->islands[$i]['id']] = $i;
    }
    fclose($fp);
    return true;
  }
  //---------------------------------------------------
  // ���ЂƂǂݍ���
  //---------------------------------------------------
  function readIsland($fp, $num) {
    global $init;
    $name     = chop(fgets($fp, READ_LINE));
    list($name, $owner, $monster) = split(",", $name);
    $id       = chop(fgets($fp, READ_LINE));
    $prize    = chop(fgets($fp, READ_LINE));
    $absent   = chop(fgets($fp, READ_LINE));
    $comment  = chop(fgets($fp, READ_LINE));
    list($comment, $comment_turn) = split(",", $comment);
    $password = chop(fgets($fp, READ_LINE));
    $money    = chop(fgets($fp, READ_LINE));
    $food     = chop(fgets($fp, READ_LINE));
    $pop      = chop(fgets($fp, READ_LINE));
    $area     = chop(fgets($fp, READ_LINE));
    $farm     = chop(fgets($fp, READ_LINE));
    $factory  = chop(fgets($fp, READ_LINE));
    $mountain = chop(fgets($fp, READ_LINE));

    $this->idToName[$id] = $name;
    
    if(($num == -1) || ($num == $id)) {
      $fp_i = fopen("{$init->dirName}/island.{$id}", "r");

      // �n�`
      $offset = 4; // ��΂̃f�[�^����������
      for($y = 0; $y < $init->islandSize; $y++) {
        $line = chop(fgets($fp_i, READ_LINE));
        for($x = 0; $x < $init->islandSize; $x++) {
          $l = substr($line, $x * $offset    , 2);
          $v = substr($line, $x * $offset + 2, 2);
          $land[$x][$y]      = hexdec($l);
          $landValue[$x][$y] = hexdec($v);
        }
      }
      
      // �R�}���h
      for($i = 0; $i < $init->commandMax; $i++) {
        $line = chop(fgets($fp_i, READ_LINE));
        list($kind, $target, $x, $y, $arg) = split(",", $line);
        $command[$i] = array (
          'kind'   => $kind,
          'target' => $target,
          'x'      => $x,
          'y'      => $y,
          'arg'    => $arg,
          );
      }
      // ���[�J���f����
      for($i = 0; $i < $init->lbbsMax; $i++) {
        $line = chop(fgets($fp_i, READ_LINE));
        $lbbs[$i] = $line;
      }
      fclose($fp_i);
    }
    return array(
      'name'     => $name,
      'owner'    => $owner,
      'id'       => $id,
      'prize'    => $prize,
      'absent'   => $absent,
      'comment'  => $comment,
      'comment_turn' => $comment_turn,
      'password' => $password,
      'money'    => $money,
      'food'     => $food,
      'pop'      => $pop,
      'area'     => $area,
      'farm'     => $farm,
      'factory'  => $factory,
      'mountain' => $mountain,
      'monster'  => $monster,
      'land'     => $land,
      'landValue'=> $landValue,
      'command'  => $command,
      'lbbs'     => $lbbs,
      );
  }
  //---------------------------------------------------
  // �S���f�[�^����������
  //---------------------------------------------------
  function writeIslandsFile($num = 0) {
    global $init;
    $fileName = "{$init->dirName}/hakojima.dat";

    if(!is_file($fileName))
      touch($fileName);

    $fp = fopen($fileName, "w");

    fputs($fp, $this->islandTurn . "\n");
    fputs($fp, $this->islandLastTime . "\n");
    fputs($fp, $this->islandNumber . "\n");
    fputs($fp, $this->islandNextID . "\n");
    for($i = 0; $i < $this->islandNumber; $i++) {
      $this->writeIsland($fp, $num, $this->islands[$i]);
    }
    fclose($fp);
//    chmod($fileName, 0666);
  }
  //---------------------------------------------------
  // ���ЂƂ�������
  //---------------------------------------------------
  function writeIsland($fp, $num, $island) {
    global $init;
    fputs($fp, $island['name'] . "," . $island['owner'] . "," . $island['monster'] . "\n");
    fputs($fp, $island['id'] . "\n");
    fputs($fp, $island['prize'] . "\n");
    fputs($fp, $island['absent'] . "\n");
    fputs($fp, $island['comment'] . "," . $island['comment_turn'] . "\n");
    fputs($fp, $island['password'] . "\n");
    fputs($fp, $island['money'] . "\n");
    fputs($fp, $island['food'] . "\n");
    fputs($fp, $island['pop'] . "\n");
    fputs($fp, $island['area'] . "\n");
    fputs($fp, $island['farm'] . "\n");
    fputs($fp, $island['factory'] . "\n");
    fputs($fp, $island['mountain'] . "\n");
    // �n�`
    if(($num <= -1) || ($num == $island['id'])) {
      $fileName = "{$init->dirName}/island.{$island['id']}";

      if(!is_file($fileName))
        touch($fileName);

      $fp_i = fopen($fileName, "w");
      $land = $island['land'];
      $landValue = $island['landValue'];
  
      for($y = 0; $y < $init->islandSize; $y++) {
        for($x = 0; $x < $init->islandSize; $x++) {
          $l = sprintf("%02x%02x", $land[$x][$y], $landValue[$x][$y]);
          fputs($fp_i, $l);
        }
        fputs($fp_i, "\n");
      }

      // �R�}���h
      $command = $island['command'];
      for($i = 0; $i < $init->commandMax; $i++) {
        $com = sprintf("%d,%d,%d,%d,%d\n",
                     $command[$i]['kind'],
                     $command[$i]['target'],
                     $command[$i]['x'],
                     $command[$i]['y'],
                     $command[$i]['arg']
                );
        fputs($fp_i, $com);
      }

      // ���[�J���f����
      $lbbs = $island['lbbs'];
      for($i = 0; $i < $init->lbbsMax; $i++) {
        fputs($fp_i, $lbbs[$i] . "\n");
      }
      fclose($fp_i);
//      chmod($fileName, 0666);
    }
  }
  //---------------------------------------------------
  // �f�[�^�̃o�b�N�A�b�v
  //---------------------------------------------------
  function backUp() {
    global $init;

    if($init->backupTimes <= 0)
      return;
    
    $tmp = $init->backupTimes - 1;
    $this->rmTree("{$init->dirName}.bak{$tmp}");
    for($i = ($init->backupTimes - 1); $i > 0; $i--) {
      $j = $i - 1;
      if(is_dir("{$init->dirName}.bak{$j}"))
        rename("{$init->dirName}.bak{$j}", "{$init->dirName}.bak{$i}");
    }
    if(is_dir("{$init->dirName}"))
      rename("{$init->dirName}", "{$init->dirName}.bak0");

    mkdir("{$init->dirName}", $init->dirMode);

    // ���O�t�@�C�������߂�
    for($i = 0; $i <= $init->logMax; $i++) {
      if(is_file("{$init->dirName}.bak0/hakojima.log{$i}"))
        rename("{$init->dirName}.bak0/hakojima.log{$i}", "{$init->dirName}/hakojima.log{$i}");
    }
    if(is_file("{$init->dirName}.bak0/hakojima.his"))
      rename("{$init->dirName}.bak0/hakojima.his", "{$init->dirName}/hakojima.his");
  }
  //---------------------------------------------------
  // �s�v�ȃf�B���N�g���ƃt�@�C�����폜
  //---------------------------------------------------
  function rmTree($dirName) {
    if(is_dir("{$dirName}")) {
      $dir = opendir("{$dirName}/");
      while($fileName = readdir($dir)) {
        if(!(strcmp($fileName, ".") == 0 || strcmp($fileName, "..") == 0))
          unlink("{$dirName}/{$fileName}");
      }
      closedir($dir);
      rmdir($dirName);
    }
  }
}
//--------------------------------------------------------------------
class LogIO {
  var $logPool = array();
  var $secretLogPool = array();
  var $lateLogPool = array();
  
  //---------------------------------------------------
  // ���O�t�@�C�������ɂ��炷
  //---------------------------------------------------
  function slideBackLogFile() {
    global $init;
    for($i = $init->logMax - 1; $i >= 0; $i--) {
      $j = $i + 1;
      $s = "{$init->dirName}/hakojima.log{$i}";
      $d = "{$init->dirName}/hakojima.log{$j}";
      if(is_file($s)) {
        if(is_file($d))
           unlink($d);
        rename($s, $d);
      }
    }
  }
  //---------------------------------------------------
  // �ŋ߂̏o�������o��
  //---------------------------------------------------
  function logFilePrint($num = 0, $id = 0, $mode = 0) {
    global $init;
    $fileName = $init->dirName . "/hakojima.log" . $num;
    if(!is_file($fileName)) {
      return;
    }
    $fp = fopen($fileName, "r");

    while($line = chop(fgets($fp, READ_LINE))) {
      list($m, $turn, $id1, $id2, $message) = split(",", $line, 5);
      if($m == 1) {
        if(($mode == 0) || ($id1 != $id)) {
          continue;
        }
        $m = "<strong>(�@��)</strong>";
      } else {
        $m = "";
      }
      if($id != 0) {
        if(($id != $id1) && ($id != $id2)) {
          continue;
        }
      }
      print "{$init->tagNumber_}�^�[��{$turn}{$m}{$init->_tagNumber}�F{$message}<br>\n";
    }
    fclose($fp);
  }
  //---------------------------------------------------
  // �����̋L�^���o��
  //---------------------------------------------------
  function historyPrint() {
    global $init;
    $fileName = $init->dirName . "/hakojima.his";
    if(!is_file($fileName)) {
      return;
    }
    $fp = fopen($fileName, "r");

    $history = array();
    $k = 0;
    while($line = chop(fgets($fp, READ_LINE))) {
      array_push($history, $line);
      $k++;
    }
    for($i = 0; $i < $k; $i++) {
      list($turn, $his) = split(",", array_pop($history), 2);
      print "{$init->tagNumber_}�^�[��{$turn}{$init->_tagNumber}�F$his<br>\n";
    }
  }
  //---------------------------------------------------
  // �����̋L�^��ۑ�
  //---------------------------------------------------
  function history($str) {
    global $init;
    $fileName = "{$init->dirName}/hakojima.his";

    if(!is_file($fileName))
      touch($fileName);

    $fp = fopen($fileName, "a");
    fputs($fp, "{$GLOBALS['ISLAND_TURN']},{$str}\n");
    fclose($fp);
//    chmod($fileName, 0666);
    
  }
  //---------------------------------------------------
  // �����̋L�^���O����
  //---------------------------------------------------
  function historyTrim() {
    global $init;
    $fileName = "{$init->dirName}/hakojima.his";
    if(is_file($fileName)) {
      $fp = fopen($fileName, "r");

      $line = array();
      while($l = chop(fgets($fp, READ_LINE))) {
        array_push($line, $l);
        $count++;
      }
      fclose($fp);
      if($count > $init->historyMax) {

        if(!is_file($fileName))
          touch($fileName);

        $fp = fopen($fileName, "w");
        for($i = ($count - $init->historyMax); $i < $count; $i++) {
          fputs($fp, "{$line[$i]}\n");
        }
        fclose($fp);
//        chmod($fileName, 0666);
      }
    }
  }
  //---------------------------------------------------
  // ���O
  //---------------------------------------------------
  function out($str, $id = "", $tid = "") {
    array_push($this->logPool, "0,{$GLOBALS['ISLAND_TURN']},{$id},{$tid},{$str}");
  }
  //---------------------------------------------------
  // �@�����O
  //---------------------------------------------------
  function secret($str, $id = "", $tid = "") {
    array_push($this->secretLogPool,"1,{$GLOBALS['ISLAND_TURN']},{$id},{$tid},{$str}");
  }
  //---------------------------------------------------
  // �x�����O
  //---------------------------------------------------
  function late($str, $id = "", $tid = "") {
    array_push($this->lateLogPool,"0,{$GLOBALS['ISLAND_TURN']},{$id},{$tid},{$str}");
  }
  //---------------------------------------------------
  // ���O�����o��
  //---------------------------------------------------
  function flush() {
    global $init;
    $fileName = "{$init->dirName}/hakojima.log0";

    if(!is_file($fileName))
      touch($fileName);

    $fp = fopen($fileName, "w");

    // �S���t���ɂ��ď����o��
    if(!empty($this->secretLogPool)) {
      for($i = count($this->secretLogPool) - 1; $i >= 0; $i--) {
        fputs($fp, "{$this->secretLogPool[$i]}\n");
      }
    }
    if(!empty($this->lateLogPool)) {
      for($i = count($this->lateLogPool) - 1; $i >= 0; $i--) {
        fputs($fp, "{$this->lateLogPool[$i]}\n");
      }
    }
    if(!empty($this->logPool)) {
      for($i = count($this->logPool) - 1; $i >= 0; $i--) {
        fputs($fp, "{$this->logPool[$i]}\n");
      }
    }
    fclose($fp);
//    chmod($fileName, 0666);
  }    
}

//--------------------------------------------------------------------
class Util {
  //---------------------------------------------------
  // �����̕\��
  //---------------------------------------------------
  function aboutMoney($money = 0) {
    global $init;
    if($init->moneyMode) {
      if($money < 500) {
        return "����500{$init->unitMoney}����";
      } else {
        return "����" . round($money / 1000) . "000" . $init->unitMoney;
      }
    } else {
      return $money . $init->unitMoney;
    }
  }
  //---------------------------------------------------
  // �o���n����~�T�C����n���x�����Z�o
  //---------------------------------------------------
  function expToLevel($kind, $exp) {
    global $init;
    if($kind == $init->landBase) {
      // �~�T�C����n
      for($i = $init->maxBaseLevel; $i > 1; $i--) {
        if($exp >= $init->baseLevelUp[$i - 2]) {
          return $i;
        }
      }
      return 1;
    } else {
      // �C���n
      for($i = $init->maxSBaseLevel; $i > 1; $i--) {
        if($exp >= $init->sBaseLevelUp[$i - 2]) {
          return $i;
        }
      }
      return 1;
    }
  }
  //---------------------------------------------------
  // ���b�̎�ށE���O�E�̗͂��Z�o
  //---------------------------------------------------
  function monsterSpec($lv) {
    global $init;
    // ���
    $kind = (int)($lv / 10);
    // ���O
    $name = $init->monsterName[$kind];
    // �̗�
    $hp = $lv - ($kind * 10);
    return array ( 'kind' => $kind, 'name' => $name, 'hp' => $hp );
  }
  //---------------------------------------------------
  // ���̖��O����ԍ����Z�o
  //---------------------------------------------------
  function  nameToNumber($hako, $name) {
    // �S������T��
    for($i = 0; $i < $hako->islandNumber; $i++) {
      if(strcmp($name, "{$hako->islands[$i]['name']}") == 0) {
        return $i;
      }
    }
    // ������Ȃ������ꍇ
    return -1;
  }
  //---------------------------------------------------
  // �p�X���[�h�`�F�b�N
  //---------------------------------------------------
  function checkPassword($p1 = "", $p2 = "") {
    global $init;

    // null�`�F�b�N
    if(empty($p2))
      return false;

    // �}�X�^�[�p�X���[�h�`�F�b�N
    if(strcmp($init->masterPassword, $p2) == 0)
      return true;

    if(strcmp($p1, Util::encode($p2)) == 0)
      return true;
    
    return false;
  }
  //---------------------------------------------------
  // �p�X���[�h�̃G���R�[�h
  //---------------------------------------------------
  function encode($s) {
    global $init;
    if($init->cryptOn) {
      return crypt($s, 'h2');
    } else {
      return $s;
    }
  }
  //---------------------------------------------------
  // 0 �` num -1 �̗�������
  //---------------------------------------------------
  function random($num = 0) {
    if($num <= 1) return 0;
    return mt_rand(0, $num - 1);
  }
  //---------------------------------------------------
  // ���[�J���f���̃��b�Z�[�W����O�ɂ��炷
  //---------------------------------------------------
  function slideBackLbbsMessage(&$lbbs, $num) {
    global $init;
    array_splice($lbbs, $num, 1);
    $lbbs[$init->lbbsMax - 1] = '0>>';
  }
  //---------------------------------------------------
  // ���[�J���f���̃��b�Z�[�W������ɂ��炷
  //---------------------------------------------------
  function slideLbbsMessage(&$lbbs) {
    array_pop($lbbs);
    array_unshift($lbbs, $lbbs[0]);
  }
  //---------------------------------------------------
  // �����_���ȍ��W�𐶐�
  //---------------------------------------------------
  function makeRandomPointArray() {
    global $init;
    $rx = $ry = array();
    for($i = 0; $i < $init->islandSize; $i++)
      for($j = 0; $j < $init->islandSize; $j++)
        $rx[$i * $init->islandSize + $j] = $j;

    for($i = 0; $i < $init->islandSize; $i++)
      for($j = 0; $j < $init->islandSize; $j++)
        $ry[$j * $init->islandSize + $i] = $j;
    

    for($i = $init->pointNumber; --$i;) {
      $j = Util::random($i + 1);
      if($i != $j) {
        $tmp = $rx[$i];
        $rx[$i] = $rx[$j];
        $rx[$j] = $tmp;
          
        $tmp = $ry[$i];
        $ry[$i] = $ry[$j];
        $ry[$j] = $tmp;
      }
    }
    return array($rx, $ry);
  }
  //---------------------------------------------------
  // �����_���ȓ��̏����𐶐�
  //---------------------------------------------------
  function randomArray($n = 1) {
    // �����l
    for($i = 0; $i < $n; $i++) {
      $list[$i] = $i;
    }

    // �V���b�t��
    for($i = 0; $i < $n; $i++) {
      $j = Util::random($n - 1);
      if($i != $j) {
        $tmp = $list[$i];
        $list[$i] = $list[$j];
        $list[$j] = $tmp;
      }
    }
    return $list;
  }
  //---------------------------------------------------
  // �R�}���h��O�ɂ��炷
  //---------------------------------------------------
  function slideFront(&$command, $number = 0) {
    global $init;
    // ���ꂼ�ꂸ�炷
    array_splice($command, $number, 1);

    // �Ō�Ɏ����J��
    $command[$init->commandMax - 1] = array (
      'kind'   => $init->comDoNothing,
      'target' => 0,
      'x'      => 0,
      'y'      => 0,
      'arg'    => 0
      );
  }
  //---------------------------------------------------
  // �R�}���h����ɂ��炷
  //---------------------------------------------------
  function slideBack(&$command, $number = 0) {
    global $init;
    // ���ꂼ�ꂸ�炷
    if($number == count($command) - 1)
      return;

    for($i = $init->commandMax - 1; $i >= $number; $i--) {
      $command[$i] = $command[$i - 1];
    }
  }

  function euc_convert($arg) {
    // �����R�[�h��EUC-JP�ɕϊ����ĕԂ�
    // ������̕����R�[�h�𔻕�
    $code = i18n_discover_encoding("$arg");
    // ��EUC-JP�̏ꍇ�̂�EUC-JP�ɕϊ�
    if ( $code != "EUC-JP" ) {
      $arg = i18n_convert("$arg","EUC-JP");
    }
    return $arg;
  }

  function sjis_convert($arg) {
    // �����R�[�h��SHIFT_JIS�ɕϊ����ĕԂ�
    // ������̕����R�[�h�𔻕�
    $code = i18n_discover_encoding("$arg");
    // ��SHIFT_JIS�̏ꍇ�̂�SHIFT_JIS�ɕϊ�
    if ( $code != "SJIS" ) {
      $arg = i18n_convert("$arg","SJIS");
    }
    return $arg;
  }
  //---------------------------------------------------
  // �t�@�C�������b�N����
  //---------------------------------------------------
  function lock() {
    global $init;

    $fp = fopen("{$init->dirName}/lock.dat", "w");

    for($count = 0; $count < LOCK_RETRY_COUNT; $count++) {
      if(flock($fp, LOCK_EX)) {
        // ���b�N����
        return $fp;
      }
      // ��莞��sleep���A���b�N�����������̂�҂�
      // ��������sleep���邱�ƂŁA���b�N�����x���Փ˂��Ȃ��悤�ɂ���
      usleep((LOCK_RETRY_INTERVAL - mt_rand(0, 300)) * 1000);
    }
    // ���b�N���s
    fclose($fp);
    Error::lockFail();
    return FALSE;
  }
  //---------------------------------------------------
  // �t�@�C�����A�����b�N����
  //---------------------------------------------------
  function unlock($fp) {
    flock($fp, LOCK_UN);
    fclose($fp);
  }
}
class Cgi {
  var $mode = "";
  var $dataSet = array();
  //---------------------------------------------------
  // POST�AGET�̃f�[�^���擾
  //---------------------------------------------------
  function parseInputData() {
    global $init;

    $this->mode = $_POST['mode'];
    if(!empty($_POST)) {
      while(list($name, $value) = each($_POST)) {
//        $value = Util::sjis_convert($value);
        // ���p�J�i������ΑS�p�ɕϊ����ĕԂ�
//        $value = i18n_ja_jp_hantozen($value,"KHV");
        $value = str_replace(",", "", $value);
        $value = JcodeConvert($value, 0, 2);
        $value = HANtoZEN_SJIS($value);
        if($init->stripslashes == true) {
          $this->dataSet["{$name}"] = stripslashes($value);
        } else {
          $this->dataSet["{$name}"] = $value;
        }
      }
    }
    if(!empty($_GET['Sight'])) {
      $this->mode = "print";
      $this->dataSet['ISLANDID'] = $_GET['Sight'];
    }
    if(!empty($_GET['target'])) {
      $this->mode = "targetView";
      $this->dataSet['ISLANDID'] = $_GET['target'];
    }
    if($_GET['mode'] == "conf") {
      $this->mode = "conf";
    }
    if($this->mode == "turn") {
      // ���̒i�K�� mode �� turn ���Z�b�g�����͕̂s���A�N�Z�X������ꍇ�݂̂Ȃ̂ŃN���A����
      $this->mode = '';
    }
    $this->dataSet["ISLANDNAME"] = jsubstr($this->dataSet["ISLANDNAME"], 0, 16);
    $this->dataSet["MESSAGE"] = jsubstr($this->dataSet["MESSAGE"], 0, 60);
    $this->dataSet["LBBSMESSAGE"] = jsubstr($this->dataSet["LBBSMESSAGE"], 0, 60);
  }
  function lastModified() {
    global $init;

    // Last Modified�w�b�_���o��
/*
    if($this->mode == "Sight") {
      $fileName = "{$init->dirName}/island.{$this->dataSet['ISLANDID']}";
    } else {
      $fileName = "{$init->dirName}/hakojima.dat";
    }
*/
    $fileName = "{$init->dirName}/hakojima.dat";
    $time_stamp = filemtime($fileName);
    $time = gmdate("D, d M Y G:i:s", $time_stamp);
    header ("Last-Modified: $time GMT");
    $this->modifiedSinces($time_stamp);
  }
  function modifiedSinces($time) {
    $modsince = $_SERVER{'HTTP_IF_MODIFIED_SINCE'};

    $ms = gmdate("D, d M Y G:i:s", $time) . " GMT";
    if($modsince == $ms)
      // RFC 822
      header ("HTTP/1.1 304 Not Modified\n");

    $ms = gmdate("l, d-M-y G:i:s", $time) . " GMT";
    if($modsince == $ms)
      // RFC 850
      header ("HTTP/1.1 304 Not Modified\n");

    $ms = gmdate("D M j G:i:s Y", $time);
    if($modsince == $ms)
      // ANSI C's asctime() format
      header ("HTTP/1.1 304 Not Modified\n");
  }
  //---------------------------------------------------
  // COOKIE���擾
  //---------------------------------------------------
  function getCookies() {
    if(!empty($_COOKIE)) {
      while(list($name, $value) = each($_COOKIE)) {
        switch($name) {
        case "OWNISLANDID":
          $this->dataSet['defaultID'] = $value;
          break;
        case "OWNISLANDPASSWORD":
          $this->dataSet['defaultPassword'] = $value;
          break;
        case "TARGETISLANDID":
          $this->dataSet['defaultTarget'] = $value;
          break;
        case "LBBSNAME":
          $this->dataSet['defaultName'] = $value;
          break;
        case "POINTX":
          $this->dataSet['defaultX'] = $value;
          break;
        case "POINTY":
          $this->dataSet['defaultY'] = $value;
          break;
        case "COMMAND":
          $this->dataSet['defaultKind'] = $value;
          break;
        case "DEVELOPEMODE":
          $this->dataSet['defaultDevelopeMode'] = $value;
          break;
        case "SKIN":
          $this->dataSet['defaultSkin'] = $value;
          break;
        }
      }
    }
  }
  //---------------------------------------------------
  // COOKIE�𐶐�
  //---------------------------------------------------
  function setCookies() {
    $time = time() + 30 * 86400; // ���� + 30���L��

    // Cookie�̐ݒ� & POST�œ��͂��ꂽ�f�[�^�ŁACookie����擾�����f�[�^���X�V
    if($this->dataSet['ISLANDID'] && $this->mode == "owner") {
      setcookie("OWNISLANDID",$this->dataSet['ISLANDID'], $time);
      $this->dataSet['defaultID'] = $this->dataSet['ISLANDID'];
    }
    if($this->dataSet['PASSWORD']) {
      setcookie("OWNISLANDPASSWORD",$this->dataSet['PASSWORD'], $time);
      $this->dataSet['defaultPassword'] = $this->dataSet['PASSWORD'];
    }
    if($this->dataSet['TARGETID']) {
      setcookie("TARGETISLANDID",$this->dataSet['TARGETID'], $time);
      $this->dataSet['defaultTarget'] = $this->dataSet['TARGETID'];
    }
    if($this->dataSet['LBBSNAME']) {
      setcookie("LBBSNAME",$this->dataSet['LBBSNAME'], $time);
      $this->dataSet['defaultName'] = $this->dataSet['LBBSNAME'];
    }
    if($this->dataSet['POINTX']) {
      setcookie("POINTX",$this->dataSet['POINTX'], $time);
      $this->dataSet['defaultX'] = $this->dataSet['POINTX'];
    }
    if($this->dataSet['POINTY']) {
      setcookie("POINTY",$this->dataSet['POINTY'], $time);
      $this->dataSet['defaultY'] = $this->dataSet['POINTY'];
    }
    if($this->dataSet['COMMAND']) {
      setcookie("COMMAND",$this->dataSet['COMMAND'], $time);
      $this->dataSet['defaultKind'] = $this->dataSet['COMMAND'];
    }
    if($this->dataSet['DEVELOPEMODE']) {
      setcookie("DEVELOPEMODE",$this->dataSet['DEVELOPEMODE'], $time);
      $this->dataSet['defaultDevelopeMode'] = $this->dataSet['DEVELOPEMODE'];
    }
    if($this->dataSet['SKIN']) {
      setcookie("SKIN",$this->dataSet['SKIN'], $time);
      $this->dataSet['defaultSkin'] = $this->dataSet['SKIN'];
    }
  }
}


//--------------------------------------------------------------------
class Main {

  function execute() {
    $hako = new Hako;
    $cgi = new Cgi;
    
    $cgi->parseInputData();
    $cgi->getCookies();

    $lock = Util::lock($fp);
    if(FALSE == $lock) {
      exit;
    }

    if(!$hako->readIslands($cgi)) {
      HTML::header($cgi->dataSet);
      Error::noDataFile();
      HTML::footer();
      Util::unlock($lock);
      exit();
    }
    $cgi->setCookies();
    $cgi->lastModified();

    if($cgi->dataSet['DEVELOPEMODE'] == "java") {
      $html = new HtmlJS;
      $com = new MakeJS;
    } else {
      $html = new HtmlMap;
      $com = new Make;
    }
    switch($cgi->mode) {
    case "turn":
      $turn = new Turn;
      $html = new HtmlTop;
      $html->header($cgi->dataSet);
      $turn->turnMain($hako, $cgi->dataSet); 
      $html->main($hako, $cgi->dataSet); // �^�[��������ATOP�y�[�Wopen
      $html->footer();
      break;
    case "owner":
      $html->header($cgi->dataSet);
      $html->owner($hako, $cgi->dataSet);
      $html->footer();
      break;
    case "command":
      $html->header($cgi->dataSet);
      $com->commandMain($hako, $cgi->dataSet);
      $html->footer();
      break;
      
    case "new":
      $html->header($cgi->dataSet);
      $com->newIsland($hako, $cgi->dataSet);
      $html->footer();
      break;
    case "comment":
      $html->header($cgi->dataSet);
      $com->commentMain($hako, $cgi->dataSet);
      $html->footer();
      break;
      
    case "print":
      $html->header($cgi->dataSet);
      $html->visitor($hako, $cgi->dataSet);
      $html->footer();
      break;
    case "targetView":
      $html->header($cgi->dataSet);
      $html->printTarget($hako, $cgi->dataSet);
      $html->footer();
      break;
    case "change":
      $html->header($cgi->dataSet);
      $com->changeMain($hako, $cgi->dataSet);
      $html->footer();
      break;
    case "ChangeOwnerName":
      $html->header($cgi->dataSet);
      $com->changeOwnerName($hako, $cgi->dataSet);
      $html->footer();
      break;
    case "lbbs":
      $lbbs = new Make;
      $html->header($cgi->dataSet);
      $lbbs->localBbsMain($hako, $cgi->dataSet);
      $html->footer();
      break;
      
    case "skin":
      $html = new HtmlSetted;
      $html->header($cgi->dataSet);
      $html->setSkin();
      $html->footer();
      break;
    case "conf":
      $html = new HtmlTop;
      $html->header($cgi->dataSet);
      $html->regist($hako);
      $html->footer();
      break;
      
    default: 
      $html = new HtmlTop;
      $html->header($cgi->dataSet);
      $html->main($hako, $cgi->dataSet);
      $html->footer();
    }
    Util::unlock($lock);
    exit();
  }
}
$start = new Main;
$start->execute();
?>