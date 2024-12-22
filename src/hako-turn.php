<?php
/*******************************************************************

  ���돔���Q for PHP

  
  $Id: hako-turn.php,v 1.12 2004/12/14 12:25:38 Watson Exp $

*******************************************************************/

require 'hako-log.php';

class Make {
  //---------------------------------------------------
  // ���̐V�K�쐬���[�h
  //---------------------------------------------------
  function newIsland($hako, $data) {
    global $init;
    $log = new Log;
    if($hako->islandNumber >= $init->maxIsland) {
      Error::newIslandFull();
      return;
    }
    if(empty($data['ISLANDNAME'])) {
      Error::newIslandNoName();
      return;
    }
    // ���O���������`�F�b�N
    if(ereg("[,?()<>$]", $data['ISLANDNAME']) || strcmp($data['ISLANDNAME'], "���l") == 0) {
      Error::newIslandBadName();
      return;
    }
    // ���O�̏d���`�F�b�N
    if(Util::nameToNumber($hako, $data['ISLANDNAME']) != -1) {
      Error::newIslandAlready();
      return;
    }
    // �p�X���[�h�̑��ݔ���
    if(empty($data['PASSWORD'])) {
      Error::newIslandNoPassword();
      return;
    }
    if(strcmp($data['PASSWORD'], $data['PASSWORD2']) != 0) {
      Error::wrongPassword();
      return;
    }
    // �V�������̔ԍ������߂�
    $newNumber = $hako->islandNumber;
    $hako->islandNumber++;
    $island = $this->makeNewIsland();

    // �e��̒l��ݒ�
    $island['name']  = htmlspecialchars($data['ISLANDNAME']);
    $island['owner'] = htmlspecialchars($data['OWNERNAME']);
    $island['id']    = $hako->islandNextID;
    $hako->islandNextID++;
    $island['absent'] = $init->giveupTurn - 3;
    $island['comment'] = '(���o�^)';
    $island['comment_turn'] = $hako->islandTurn;
    $island['password'] = Util::encode($data['PASSWORD']);

    Turn::estimate($island);
    $hako->islands[$newNumber] = $island;
    $hako->writeIslandsFile($island['id']);

    $log->discover($island['name']);

    $htmlMap = new HtmlMap;
    $htmlMap->newIslandHead($island['name']);
    $htmlMap->islandInfo($island, $newNumber);
    $htmlMap->islandMap($hako, $island, 1, $data);
    
  }
  //---------------------------------------------------
  // �V���������쐬����
  //---------------------------------------------------
  function makeNewIsland() {
    global $init;
    $command = array();
    // �����R�}���h����
    for($i = 0; $i < $init->commandMax; $i++) {
      $command[$i] = array (
        'kind'   => $init->comDoNothing,
        'target' => 0,
        'x'      => 0,
        'y'      => 0,
        'arg'    => 0,
        );
    }
    $lbbs = "";
    // �����f������
    for($i = 0; $i < $init->lbbsMax; $i++) {
      $lbbs[$i] = "0>>";
    }
    $land = array();
    $landValue = array();
    // ��{�`���쐬
    for($y = 0; $y < $init->islandSize; $y++) {
      for($x = 0; $x < $init->islandSize; $x++) {
        $land[$x][$y]      = $init->landSea;
        $landValue[$x][$y] = 0;
      }
    }
    
    // 4*4�ɍr�n��z�u
    $center = $init->islandSize / 2 - 1;
    for($y = $center -1; $y < $center + 3; $y++) {
      for($x = $center - 1; $x < $center + 3; $x++) {
        $land[$x][$y] = $init->landWaste;
      }
    }
    // 8*8�͈͓��ɗ��n�𑝐B
    for($i = 0; $i < 120; $i++) {
      $x = Util::random(8) + $center - 3;
      $y = Util::random(8) + $center - 3;
      if(Turn::countAround($land, $x, $y, $init->landSea, 7) != 7) {
        // ����ɗ��n������ꍇ�A�󐣂ɂ���
        // �󐣂͍r�n�ɂ���
        // �r�n�͕��n�ɂ���
        if($land[$x][$y] == $init->landWaste) {
          $land[$x][$y] = $init->landPlains;
          $landValue[$x][$y] = 0;
        } else {
          if($landValue[$x][$y] == 1) {
            $land[$x][$y] = $init->landWaste;
            $landValue[$x][$y] = 0;
          } else {
            $landValue[$x][$y] = 1;
          }
        }
      }
    }
    // �X�����
    $count = 0;
    while($count < 4) {
      // �����_�����W
      $x = Util::random(4) + $center - 1;
      $y = Util::random(4) + $center - 1;

      // ���������łɐX�łȂ���΁A�X�����
      if($land[$x][$y] != $init->landForest) {
        $land[$x][$y] = $init->landForest;
        $landValue[$x][$y] = 5; // �ŏ���500�{
        $count++;
      }
    }
    $count = 0;
    while($count < 2) {
      // �����_�����W
      $x = Util::random(4) + $center - 1;
      $y = Util::random(4) + $center - 1;

      // �������X�����łȂ���΁A�������
      if(($land[$x][$y] != $init->landTown) &&
         ($land[$x][$y] != $init->landForest)) {
        $land[$x][$y] = $init->landTown;
        $landValue[$x][$y] = 5; // �ŏ���500�l
        $count++;
      }
    }

    // �R�����
    $count = 0;
    while($count < 1) {
      // �����_�����W
      $x = Util::random(4) + $center - 1;
      $y = Util::random(4) + $center - 1;

      // �������X�����łȂ���΁A�������
      if(($land[$x][$y] != $init->landTown) &&
         ($land[$x][$y] != $init->landForest)) {
        $land[$x][$y] = $init->landMountain;
        $landValue[$x][$y] = 0; // �ŏ��͍̌@��Ȃ�
        $count++;
      }
    }

    // ��n�����
    $count = 0;
    while($count < 1) {
      // �����_�����W
      $x = Util::random(4) + $center - 1;
      $y = Util::random(4) + $center - 1;

      // �������X�������R�łȂ���΁A��n
      if(($land[$x][$y] != $init->landTown) &&
         ($land[$x][$y] != $init->landForest) &&
         ($land[$x][$y] != $init->landMountain)) {
        $land[$x][$y] = $init->landBase;
        $landValue[$x][$y] = 0;
        $count++;
      }
    }

    return array (
      'money'	  => $init->initialMoney,
      'food'	  => $init->initialFood,
      'land'	  => $land,
      'landValue' => $landValue,
      'command'	  => $command,
      'lbbs'	  => $lbbs,
      'prize'	  => '0,0,',
      );    
  }
  //---------------------------------------------------
  // �R�����g�X�V
  //---------------------------------------------------
  function commentMain($hako, $data) {
    $id  = $data['ISLANDID'];
    $num = $hako->idToNumber[$id];
    $island = $hako->islands[$num];
    $name = $island['name'];

    // �p�X���[�h
    if(!Util::checkPassword($island['password'], $data['PASSWORD'])) {
      // password�ԈႢ
      Error::wrongPassword();
      return;
    }
    // ���b�Z�[�W���X�V
    $island['comment'] = htmlspecialchars($data['MESSAGE']);
    $island['comment_turn'] = $hako->islandTurn;
    $hako->islands[$num] = $island;

    // �f�[�^�̏����o��
    $hako->writeIslandsFile();

    // �R�����g�X�V���b�Z�[�W
    HtmlSetted::Comment();

    
    // owner mode��
    if($data['DEVELOPEMODE'] == "cgi") {
      $html = new HtmlMap;
    } else {
      $html = new HtmlJS;
    }
    $html->owner($hako, $data);
  }
  //---------------------------------------------------
  // ���[�J���f�����[�h
  //---------------------------------------------------
  function localBbsMain($hako, $data) {
    $id  = $data['ISLANDID'];
    $num = $hako->idToNumber[$id];
    $island = $hako->islands[$num];
    $name = $island['name'];

    // �Ȃ������̓����Ȃ��ꍇ
    if(empty($data['ISLANDID'])) {
      Error::problem();
      return;
    }

    // �폜���[�h����Ȃ��Ė��O�����b�Z�[�W���Ȃ��ꍇ
    if($data['lbbsMode'] != 2) {
      if(empty($data['LBBSNAME']) || (empty($data['LBBSMESSAGE']))) {
        Error::lbbsNoMessage();
        return;
      }
    }

    // �ό��҃��[�h����Ȃ����̓p�X���[�h�`�F�b�N
    if($data['lbbsMode'] != 0) {
      if(!Util::checkPassword($island['password'], $data['PASSWORD'])) {
        // password�ԈႢ
        Error::wrongPassword();
        return;
      }
    }

    $lbbs = $island['lbbs'];

    // ���[�h�ŕ���
    if($data['lbbsMode'] == 2) {
      // �폜���[�h
      // ���b�Z�[�W��O�ɂ��炷
      Util::slideBackLbbsMessage($lbbs, $data['NUMBER']);
      HtmlSetted::lbbsDelete();
    } else {
      // �L�����[�h
      Util::slideLbbsMessage($lbbs);

      // ���b�Z�[�W��������
      if($data['lbbsMode'] == 0) {
        $message = '0';
      } else {
        $message = '1';
      }
      $bbs_name = "{$hako->islandTurn}�F" . htmlspecialchars($data['LBBSNAME']);
      $bbs_message = htmlspecialchars($data['LBBSMESSAGE']);
      $lbbs[0] = "{$message}>{$bbs_name}>{$bbs_message}";

      HtmlSetted::lbbsAdd();
    }
    $island['lbbs'] = $lbbs;
    $hako->islands[$num] = $island;

    // �f�[�^�����o��
    $hako->writeIslandsFile($id);

    if($data['DEVELOPEMODE'] == "cgi") {
      $html = new HtmlMap;
    } else {
      $html = new HtmlJS;
    }
    // ���Ƃ̃��[�h��
    if($data['lbbsMode'] == 0) {
      $html->visitor($hako, $data);
    } else {
      $html->owner($hako, $data);
    }
  }
  //---------------------------------------------------
  // ���ύX���[�h
  //---------------------------------------------------
  function changeMain($hako, $data) {
    global $init;
    $log = new Log;
    
    $id  = $data['ISLANDID'];
    $num = $hako->idToNumber[$id];
    $island = $hako->islands[$num];
    $name = $island['name'];

    // �p�X���[�h�`�F�b�N
    if(strcmp($data['OLDPASS'], $init->specialPassword) == 0) {
      // ����p�X���[�h
      $island['money'] = $init->maxMoney;
      $island['food']  = $init->maxFood;
    } elseif(!Util::checkPassword($island['password'], $data['OLDPASS'])) {
      // password�ԈႢ
      Error::wrongPassword();
      return;
    }

    // �m�F�p�p�X���[�h
    if(strcmp($data['PASSWORD'], $data['PASSWORD2']) != 0) {
      // password�ԈႢ
      Error::wrongPassword();
      return;
    }

    if(!empty($data['ISLANDNAME'])) {
      // ���O�ύX�̏ꍇ
      // ���O���������`�F�b�N
      if(ereg("[,?()<>$]", $data['ISLANDNAME']) || strcmp($data['ISLANDNAME'], "���l") == 0) {
        Error::newIslandBadName();
        return;
      }

      // ���O�̏d���`�F�b�N
      if(Util::nameToNumber($hako, $data['ISLANDNAME']) != -1) {
        Error::newIslandAlready();
        return;
      }

      if($island['money'] < $init->costChangeName) {
        // ��������Ȃ�
        Error::changeNoMoney();
        return;
      }

      // ���
      if(strcmp($data['OLDPASS'], $init->specialPassword) != 0) {
        $island['money'] -= $init->costChangeName;
      }

      // ���O��ύX
      $log->changeName($island['name'], $data['ISLANDNAME']);
      $island['name'] = $data['ISLANDNAME'];
      $flag = 1;
    }

    // password�ύX�̏ꍇ
    if(!empty($data['PASSWORD'])) {
      // �p�X���[�h��ύX
      $island['password'] = Util::encode($data['PASSWORD']);
      $flag = 1;
    }

    if(($flag == 0) && (strcmp($data['PASSWORD'], $data['PASSWORD2']) != 0)) {
      // �ǂ�����ύX����Ă��Ȃ�
      Error::changeNothing();
      return;
    }

    $hako->islands[$num] = $island;
    // �f�[�^�����o��
    $hako->writeIslandsFile($id);

    // �ύX����
    HtmlSetted::change();
  }
  //---------------------------------------------------
  // �I�[�i���ύX���[�h
  //---------------------------------------------------
  function changeOwnerName($hako, $data) {
    global $init;

    $id  = $data['ISLANDID'];
    $num = $hako->idToNumber[$id];
    $island = $hako->islands[$num];

    // �p�X���[�h�`�F�b�N
    if(strcmp($data['OLDPASS'], $init->specialPassword) == 0) {
      // ����p�X���[�h
      $island['money'] = $init->maxMoney;
      $island['food']  = $init->maxFood;
    } elseif(!Util::checkPassword($island['password'], $data['OLDPASS'])) {
      // password�ԈႢ
      Error::wrongPassword();
      return;
    }
    $island['owner'] = htmlspecialchars($data['OWNERNAME']);
    $hako->islands[$num] = $island;
    // �f�[�^�����o��
    $hako->writeIslandsFile($id);

    // �ύX����
    HtmlSetted::change();
  }
  //---------------------------------------------------
  // �R�}���h���[�h
  //---------------------------------------------------
  function commandMain($hako, $data) {
    global $init;
    $id  = $data['ISLANDID'];
    $num = $hako->idToNumber[$id];
    $island = $hako->islands[$num];
    $name = $island['name'];

    // �p�X���[�h
    if(!Util::checkPassword($island['password'], $data['PASSWORD'])) {
      // password�ԈႢ
      Error::wrongPassword();
      return;
    }

    // ���[�h�ŕ���
    $command = $island['command'];

    if(strcmp($data['COMMANDMODE'], 'delete') == 0) {
      Util::slideFront($command, $data['NUMBER']);
      HtmlSetted::commandDelete();
    } elseif(($data['COMMAND'] == $init->comAutoPrepare) ||
             ($data['COMMAND'] == $init->comAutoPrepare2)) {
      // �t�����n�A�t���n�Ȃ炵
      // ���W�z������
      $r = Util::makeRandomPointArray();
      $rpx = $r[0];
      $rpy = $r[1];
      $land = $island['land'];
      // �R�}���h�̎�ތ���
      $kind = $init->comPrepare;
      if($data['COMMAND'] == $init->comAutoPrepare2) {
        $kind = $init->comPrepare2;
      }

      $i = $data['NUMBER'];
      $j = 0;
      while(($j < $init->pointNumber) && ($i < $init->commandMax)) {
        $x = $rpx[$j];
        $y = $rpy[$j];
        if($land[$x][$y] == $init->landWaste) {
          Util::slideBack($command, $data['NUMBER']);
          $command[$data['NUMBER']] = array (
            'kind'	=> $kind,
            'target'	=> 0,
            'x'		=> $x,
            'y'		=> $y,
            'arg'	=> 0,
            );
          $i++;
        }
        $j++;
      }
      HtmlSetted::commandAdd();
    } elseif($data['COMMAND'] == $init->comAutoDelete) {
      // �S����
      for($i = 0; $i < $init->commandMax; $i++) {
        Util::slideFront($command, 0);
      }
      HtmlSetted::commandDelete();
    } else {
      if(strcmp($data['COMMANDMODE'], 'insert') == 0) {
        Util::slideBack($command, $data['NUMBER']);
      }
      HtmlSetted::commandAdd();
      // �R�}���h��o�^
      $command[$data['NUMBER']] = array (
        'kind'   => $data['COMMAND'],
        'target' => $data['TARGETID'],
        'x'      => $data['POINTX'],
        'y'      => $data['POINTY'],
        'arg'    => $data['AMOUNT'],
        );
    }

    // �f�[�^�̏����o��
    $island['command'] = $command;
    $hako->islands[$num] = $island;
    $hako->writeIslandsFile($island['id']);

    // owner mode��
    $html = new HtmlMap;
    $html->owner($hako, $data);
  }
}
class MakeJS extends Make {
  //---------------------------------------------------
  // �R�}���h���[�h
  //---------------------------------------------------
  function commandMain($hako, $data) {
    global $init;
    $id  = $data['ISLANDID'];
    $num = $hako->idToNumber[$id];
    $island = $hako->islands[$num];
    $name = $island['name'];

    // �p�X���[�h
    if(!Util::checkPassword($island['password'], $data['PASSWORD'])) {
      // password�ԈႢ
      Error::wrongPassword();
      return;
    }
    // ���[�h�ŕ���
    $command = $island['command'];
    $comary = split(" " , $data['COMARY']);
    
    for($i = 0; $i < $init->commandMax; $i++) {
      $pos = $i * 5;
      $kind   = $comary[$pos];
      $x      = $comary[$pos + 1];
      $y      = $comary[$pos + 2];
      $arg    = $comary[$pos + 3];
      $target = $comary[$pos + 4];
      // �R�}���h�o�^
      if($kind == 0) {
        $kind = $init->comDoNothing;
      }
      $command[$i] = array (
        'kind'   => $kind,
        'x'      => $x,
        'y'      => $y,
        'arg'    => $arg,
        'target' => $target
        );
    }
    HtmlSetted::commandAdd();

    // �f�[�^�̏����o��
    $island['command'] = $command;
    $hako->islands[$num] = $island;
    $hako->writeIslandsFile($island['id']);

    // owner mode��
    $html = new HtmlJS;
    $html->owner($hako, $data);
  }
  
}
//--------------------------------------------------------------------
class Turn {
  var $log;
  var $rpx;
  var $rpy;
  //---------------------------------------------------
  // �^�[���i�s���[�h
  //---------------------------------------------------
  function turnMain(&$hako, $data) {
    global $init;
    $this->log = new Log;
    
    // �ŏI�X�V���Ԃ��X�V
    $hako->islandLastTime += $init->unitTime;
    // ���O�t�@�C�������ɂ��炷
    $this->log->slideBackLogFile();

    // �^�[���ԍ�
    $hako->islandTurn++;
    $GLOBALS['ISLAND_TURN'] = $hako->islandTurn;
    if($hako->islandNumber == 0) {
      // �����Ȃ���΃^�[������ۑ����Ĉȍ~�̏����͏Ȃ�
      // �t�@�C���ɏ����o��
      $hako->writeIslandsFile();
      return;
    }

    // ���W�z������
    $randomPoint = Util::makeRandomPointArray();
    $this->rpx = $randomPoint[0];
    $this->rpy = $randomPoint[1];
    // ���Ԍ���
    $order = Util::randomArray($hako->islandNumber);

    // �����E����
    for($i = 0; $i < $hako->islandNumber; $i++) {
      $this->estimate($hako->islands[$order[$i]]);
      $this->income($hako->islands[$order[$i]]);

      // �l������������
      $hako->islands[$order[$i]]['oldPop'] = $hako->islands[$order[$i]]['pop'];
    }
    // �R�}���h����
    for($i = 0; $i < $hako->islandNumber; $i++) {
      // �߂�l1�ɂȂ�܂ŌJ��Ԃ�
      while($this->doCommand($hako, $hako->islands[$order[$i]]) == 0);
    }
    // ��������ђP�w�b�N�X�ЊQ
    for($i = 0; $i < $hako->islandNumber; $i++) {
      $this->doEachHex($hako, $hako->islands[$order[$i]]);
    }
    // ���S�̏���
    $remainNumber = $hako->islandNumber;
    for($i = 0; $i < $hako->islandNumber; $i++) {
      $island = $hako->islands[$order[$i]];
      $this->doIslandProcess($hako, $island);

      // ���Ŕ���
      if($island['dead'] == 1) {
        $island['pop'] = 0;
        $remainNumber--;
      } elseif($island['pop'] == 0) {
        $island['dead'] = 1;
        $remainNumber--;
        // ���Ń��b�Z�[�W
        $tmpid = $island['id'];
        $this->log->dead($tmpid, $island['name']);
        if(is_file("{$init->dirName}/island.{$tmpid}")) {
          unlink("{$init->dirName}/island.{$tmpid}");
        }
      }
      $hako->islands[$order[$i]] = $island;
    }
    // �l�����Ƀ\�[�g
    $this->islandSort($hako);
    // �^�[���t�Ώۃ^�[����������A���̏���
    if(($hako->islandTurn % $init->turnPrizeUnit) == 0) {
      $island = $hako->islands[0];
      $this->log->prize($island['id'], $island['name'], "{$hako->islandTurn}{$init->prizeName[0]}");
      $hako->islands[0]['prize'] .= "{$hako->islandTurn},";
    }
    // �����J�b�g
    $hako->islandNumber = $remainNumber;

    // �o�b�N�A�b�v�^�[���ł���΁A�����O��rename
    if(($hako->islandTurn % $init->backupTurn) == 0) {
      $hako->backUp();
    }
    // �t�@�C���ɏ����o��
    $hako->writeIslandsFile(-1);

    // ���O�����o��
    $this->log->flush();

    // �L�^���O����
    $this->log->historyTrim();

  }
  //---------------------------------------------------
  // �R�}���h�t�F�C�Y
  //---------------------------------------------------
  function doCommand(&$hako, &$island) {
    global $init;

    $comArray = &$island['command'];
    $command  = $comArray[0];
    Util::slideFront(&$comArray, 0);
    $island['command'] = $comArray;
    
    $kind   = $command['kind'];
    $target = $command['target'];
    $x      = $command['x'];
    $y      = $command['y'];
    $arg    = $command['arg'];

    $name = $island['name'];
    $id   = $island['id'];
    $land = $island['land'];
    $landValue = &$island['landValue'];
    $landKind = &$land[$x][$y];
    $lv   = $landValue[$x][$y];
    $cost = $init->comCost[$kind];
    $comName = $init->comName[$kind];
    $point = "({$x},{$y})";
    $landName = $this->landName($landKind, $lv);

    $prize = &$island['prize'];

    if($kind == $init->comDoNothing) {
      //$this->log->doNothing($id, $name, $comName);
      $island['money'] += 10;
      $island['absent']++;
      // ��������
      if($island['absent'] >= $init->giveupTurn) {
        $comArray[0] = array (
          'kind'   => $init->comGiveup,
          'target' => 0,
          'x'      => 0,
          'y'      => 0,
          'arg'    => 0
          );
        $island['command'] = $comArray;
      }
      return 1;
    }
    $island['command'] = $comArray;
    $island['absent']  = 0;
    // �R�X�g�`�F�b�N
    if($cost > 0) {
      // ���̏ꍇ
      if($island['money'] < $cost) {
        $this->log->noMoney($id, $name, $comName);
        return 0;
      }
    } elseif($cost < 0) {
      // �H���̏ꍇ
      if($island['food'] < (-$cost)) {
        $this->log->noFood($id, $name, $comName);
        return 0;
      }
    }

    $returnMode = 1;
    switch($kind) {
    case $init->comPrepare:
    case $init->comPrepare2:
      // ���n�A�n�Ȃ炵
      if(($landKind == $init->landSea) ||
         ($landKind == $init->landSbase) ||
         ($landKind == $init->landOil) ||
         ($landKind == $init->landMountain) ||
         ($landKind == $init->landMonster)) {
        // �C�A�C���n�A���c�A�R�A���b�͐��n�ł��Ȃ�
        $this->log->landFail($id, $name, $comName, $landName, $point);

        $returnMode = 0;
        break;
      }
      // �ړI�̏ꏊ�𕽒n�ɂ���
      $land[$x][$y] = $init->landPlains;
      $landValue[$x][$y] = 0;
      $this->log->landSuc($id, $name, '���n', $point);

      // ������������
      $island['money'] -= $cost;

      if($kind == $init->comPrepare2) {
        // �n�Ȃ炵
        $island['prepare2']++;

        // �^�[�������
        $returnMode = 0;
      } else {
        // ���n�Ȃ�A�������̉\������
        if(Util::random(1000) < $init->disMaizo) {
          $v = 100 + Util::random(901);
          $island['money'] += $v;
          $this->log->maizo($id, $name, $comName, $v);
        }
        $returnMode = 1;
      }
      break;
    case $init->comReclaim:
      // ���ߗ���
      if(($landKind != $init->landSea) &&
         ($landKind != $init->landOil) &&
         ($landKind != $init->landSbase)) {
        // �C�A�C���n�A���c�������ߗ��Ăł��Ȃ�
        $this->log->landFail($id, $name, $comName, $landName, $point);

        $returnMode = 0;
        break;
      }

      // ����ɗ������邩�`�F�b�N
      $seaCount =
        Turn::countAround($land, $x, $y, $init->landSea, 7) +
          Turn::countAround($land, $x, $y, $init->landOil, 7) +
            Turn::countAround($land, $x, $y, $init->landSbase, 7);

      if($seaCount == 7) {
        // �S���C�����疄�ߗ��ĕs�\
        $this->log->noLandAround($id, $name, $comName, $point);

        $returnMode = 0;
        break;
      }

      if(($landKind == $init->landSea) && ($lv == 1)) {
        // �󐣂̏ꍇ
        // �ړI�̏ꏊ���r�n�ɂ���
        $land[$x][$y] = $init->landWaste;
        $landValue[$x][$y] = 0;
        $this->log->landSuc($id, $name, $comName, $point);
        $island['area']++;

        if($seaCount <= 4) {
          // ����̊C��3�w�b�N�X�ȓ��Ȃ̂ŁA�󐣂ɂ���

          for($i = 1; $i < 7; $i++) {
            $sx = $x + $init->ax[$i];
            $sy = $y + $init->ay[$i];

            // �s�ɂ��ʒu����
            if((($sy % 2) == 0) && (($y % 2) == 1)) {
              $sx--;
            }

            if(($sx < 0) || ($sx >= $init->islandSize) ||
               ($sy < 0) || ($sy >= $init->islandSize)) {
            } else {
              // �͈͓��̏ꍇ
              if($land[$sx][$sy] == $init->landSea) {
                $landValue[$sx][$sy] = 1;
              }
            }
          }
        }
      } else {
        // �C�Ȃ�A�ړI�̏ꏊ��󐣂ɂ���
        $land[$x][$y] = $init->landSea;
        $landValue[$x][$y] = 1;
        $this->log->landSuc($id, $name, $comName, $point);
      }

      // ������������
      $island['money'] -= $cost;
      $returnMode =  1;
      break;

    case $init->comDestroy:
      // �@��
      if(($landKind == $init->landSbase) ||
         ($landKind == $init->landOil) ||
         ($landKind == $init->landMonster)) {
        // �C���n�A���c�A���b�͌@��ł��Ȃ�
        $this->log->landFail($id, $name, $comName, $landName, $point);

        $returnMode = 0;
        break;
      }

      if(($landKind == $init->landSea) && ($lv == 0)) {
        // �C�Ȃ�A���c�T��
        // �����z����
        if($arg == 0) { $arg = 1; }

        $value = min($arg * ($cost), $island['money']);
        $str = "{$value}{$init->unitMoney}";
        $p = round($value / $cost);
        $island['money'] -= $value;

        // �����邩����
        if($p > Util::random(100)) {
          // ���c������
          $this->log->oilFound($id, $name, $point, $comName, $str);
          $land[$x][$y] = $init->landOil;
          $landValue[$x][$y] = 0;
        } else {
          // ���ʌ����ɏI���
          $this->log->oilFail($id, $name, $point, $comName, $str);
        }
        $returnMode = 1;
        break;
      }

      // �ړI�̏ꏊ���C�ɂ���B�R�Ȃ�r�n�ɁB�󐣂Ȃ�C�ɁB
      if($landKind == $init->landMountain) {
        $land[$x][$y] = $init->landWaste;
        $landValue[$x][$y] = 0;
      } elseif($landKind == $init->landSea) {
        $landValue[$x][$y] = 0;
      } else {
        $land[$x][$y] = $init->landSea;
        $landValue[$x][$y] = 1;
        $island['area']--;
      }
      $this->log->landSuc($id, $name, $comName, $point);

      // ������������
      $island['money'] -= $cost;

      $returnMode = 1;
      break;

    case $init->comSellTree:
      // ����
      if($landKind != $init->landForest) {
        // �X�ȊO�͔��̂ł��Ȃ�
        $this->log->landFail($id, $name, $comName, $landName, $point);

        $returnMode = 0;
        break;
      }

      // �ړI�̏ꏊ�𕽒n�ɂ���
      $land[$x][$y] = $init->landPlains;
      $landValue[$x][$y] = 0;
      $this->log->landSuc($id, $name, $comName, $point);

      // ���p���𓾂�
      $island['money'] += $init->treeValue * $lv;

      $returnMode = 1;
      break;
    case $init->comPlant:
    case $init->comFarm:
    case $init->comFactory:
    case $init->comBase:
    case $init->comMonument:
    case $init->comHaribote:
    case $init->comDbase:
      // �n�㌚�݌n
      if(!
         (($landKind == $init->landPlains) ||
          ($landKind == $init->landTown)   ||
          (($landKind == $init->landMonument) && ($kind == $init->comMonument)) ||
          (($landKind == $init->landFarm)     && ($kind == $init->comFarm))     ||
          (($landKind == $init->landFactory)  && ($kind == $init->comFactory))  ||
          (($landKind == $init->landDefence)  && ($kind == $init->comDbase)))) {
        // �s�K���Ȓn�`
        $this->log->landFail($id, $name, $comName, $landName, $point);

        $returnMode = 0;
        break;
      }

      // ��ނŕ���
      switch($kind) {
      case $init->comPlant:
        // �ړI�̏ꏊ��X�ɂ���B
        $land[$x][$y] = $init->landForest;
        $landValue[$x][$y] = 1; // �؂͍Œ�P��
        $this->log->PBSuc($id, $name, $comName, $point);
        break;

      case $init->comBase:
        // �ړI�̏ꏊ���~�T�C����n�ɂ���B
        $land[$x][$y] = $init->landBase;
        $landValue[$x][$y] = 0; // �o���l0
        $this->log->PBSuc($id, $name, $comName, $point);
        break;

      case $init->comHaribote:
        // �ړI�̏ꏊ���n���{�e�ɂ���
        $land[$x][$y] = $init->landHaribote;
        $landValue[$x][$y] = 0;
        $this->log->hariSuc($id, $name, $comName, $init->comName[$init->comDbase], $point);
        break;

      case $init->comFarm:
        // �_��
        if($landKind == $init->landFarm) {
          // ���łɔ_��̏ꍇ
          $landValue[$x][$y] += 2; // �K�� + 2000�l
          if($landValue[$x][$y] > 50) {
            $landValue[$x][$y] = 50; // �ő� 50000�l
          }
        } else {
          // �ړI�̏ꏊ��_���
          $land[$x][$y] = $init->landFarm;
          $landValue[$x][$y] = 10; // �K�� = 10000�l
        }
        $this->log->landSuc($id, $name, $comName, $point);
        break;

      case $init->comFactory:
        // �H��
        if($landKind == $init->landFactory) {
          // ���łɍH��̏ꍇ
          $landValue[$x][$y] += 10; // �K�� + 10000�l
          if($landValue[$x][$y] > 100) {
            $landValue[$x][$y] = 100; // �ő� 100000�l
          }
        } else {
          // �ړI�̏ꏊ���H���
          $land[$x][$y] = $init->landFactory;
          $landValue[$x][$y] = 30; // �K�� = 10000�l
        }
        $this->log->landSuc($id, $name, $comName, $point);
        break;
        
      case $init->comDbase:
        // �h�q�{��
        if($landKind == $init->landDefence) {
          // ���łɖh�q�{�݂̏ꍇ
          $landValue[$x][$y] = 1; // �������u�Z�b�g
          $this->log->bombSet($id, $name, $landName, $point);
        } else {
          // �ړI�̏ꏊ��h�q�{�݂�
          $land[$x][$y] = $init->landDefence;
          $landValue[$x][$y] = 0;
          $this->log->landSuc($id, $name, $comName, $point);
        }
        break;
        
      case $init->comMonument:
        // �L�O��
        if($landKind == $init->landMonument) {
          // ���łɋL�O��̏ꍇ
          // �^�[�Q�b�g�擾
          $tn = $hako->idToNumber[$target];
          if($tn != 0 && empty($tn)) {
            // �^�[�Q�b�g�����łɂȂ�
            // �������킸�ɒ��~

            $returnMode = 0;
            break;
          }

          $hako->islands[$tn]['bigmissile']++;

          // ���̏ꏊ�͍r�n��
          $land[$x][$y] = $init->landWaste;
          $landValue[$x][$y] = 0;
          $this->log->monFly($id, $name, $landName, $point);
        } else {
          // �ړI�̏ꏊ���L�O���
          $land[$x][$y] = $init->landMonument;
          if($arg >= $init->monumentNumber) {
            $arg = 0;
          }
          $landValue[$x][$y] = $arg;
          $this->log->landSuc($id, $name, $comName, $point);
        }
        break;
      }

      // ������������
      $island['money'] -= $cost;

      // �񐔕t���Ȃ�A�R�}���h��߂�
      if(($kind == $init->comFarm) ||
         ($kind == $init->comFactory)) {
        if($arg > 1) {
          $arg--;
          Util::slideBack($comArray, 0);
          $comArray[0] = array (
            'kind'   => $kind,
            'target' => $target,
            'x'      => $x,
            'y'      => $y,
            'arg'    => $arg
            );
        }
      }

      $returnMode = 1;
      break;
      // �����܂Œn�㌚�݌n
    case $init->comMountain:
      // �̌@��
      if($landKind != $init->landMountain) {
        // �R�ȊO�ɂ͍��Ȃ�
        $this->log->landFail($id, $name, $comName, $landName, $point);

        $returnMode = 0;
        break;
      }

      $landValue[$x][$y] += 5; // �K�� + 5000�l
      if($landValue[$x][$y] > 200) {
        $landValue[$x][$y] = 200; // �ő� 200000�l
      }
      $this->log->landSuc($id, $name, $comName, $point);

      // ������������
      $island['money'] -= $cost;
      if($arg > 1) {
        $arg--;
        Util::slideBack(&$comArray, 0);
        $comArray[0] = array (
          'kind'   => $kind,
          'target' => $target,
          'x'      => $x,
          'y'      => $y,
          'arg'    => $arg,
          );
      }
      $returnMode = 1;
      break;

    case $init->comSbase:
      // �C���n
      if(($landKind != $init->landSea) || ($lv != 0)){
        // �C�ȊO�ɂ͍��Ȃ�
        $this->log->landFail($id, $name, $comName, $landName, $point);
        $returnMode = 0;
        break;
      }

      $land[$x][$y] = $init->landSbase;
      $landValue[$x][$y] = 0; // �o���l0
      $this->log->landSuc($id, $name, $comName, '(?, ?)');

      // ������������
      $island['money'] -= $cost;
      $returnMode = 1;
      break;

    case $init->comMissileNM:
    case $init->comMissilePP:
    case $init->comMissileST:
    case $init->comMissileLD:
      // �~�T�C���n
      // �^�[�Q�b�g�擾
      $tn = $hako->idToNumber[$target];
      if($tn != 0 && empty($tn)) {
        // �^�[�Q�b�g�����łɂȂ�
        $this->log->msNoTarget($id, $name, $comName);

        $returnMode = 0;
        break;
      }

      $flag = 0;
      if($arg == 0) {
        // 0�̏ꍇ�͌��Ă邾��
        $arg = 10000;
      }

      // ���O����
      $tIsland = &$hako->islands[$tn];
      $tName   = &$tIsland['name'];
      $tLand   = &$tIsland['land'];
      $tLandValue = &$tIsland['landValue'];
      // ��̐�
      $boat = 0;

      // �덷
      if($kind == $init->comMissilePP) {
        $err = 7;
      } else {
        $err = 19;
      }

      $bx = $by = 0;
      // �����s���邩�w�萔�ɑ���邩��n�S�������܂Ń��[�v
      while(($arg > 0) &&
            ($island['money'] >= $cost)) {
        // ��n��������܂Ń��[�v
        while($count < $init->pointNumber) {
          $bx = $this->rpx[$count];
          $by = $this->rpy[$count];
          if(($land[$bx][$by] == $init->landBase) ||
             ($land[$bx][$by] == $init->landSbase)) {
            break;
          }
          $count++;
        }
        if($count >= $init->pointNumber) {
          // ������Ȃ������炻���܂�
          break;
        }
        // �Œ���n���������̂ŁAflag�𗧂Ă�
        $flag = 1;
        // ��n�̃��x�����Z�o
        $level = Util::expToLevel($land[$bx][$by], $landValue[$bx][$by]);
        // ��n���Ń��[�v
        while(($level > 0) &&
              ($arg > 0) &&
              ($island['money'] > $cost)) {
          // �������̂��m��Ȃ̂ŁA�e�l�����Ղ�����
          $level--;
          $arg--;
          $island['money'] -= $cost;

          // ���e�_�Z�o
          $r = Util::random($err);
          $tx = $x + $init->ax[$r];
          $ty = $y + $init->ay[$r];
          if((($ty % 2) == 0) && (($y % 2) == 1)) {
            $tx--;
          }

          // ���e�_�͈͓��O�`�F�b�N
          if(($tx < 0) || ($tx >= $init->islandSize) ||
             ($ty < 0) || ($ty >= $init->islandSize)) {
            // �͈͊O
            if($kind == $init->comMissileST) {
              // �X�e���X
              $this->log->msOutS($id, $target, $name, $tName, $comName, $point);
            } else {
              // �ʏ�n
              $this->log->msOut($id, $target, $name, $tName, $comName, $point);
            }
            continue;
          }

          // ���e�_�̒n�`���Z�o
          $tL  = $tLand[$tx][$ty];
          $tLv = $tLandValue[$tx][$ty];
          $tLname = $this->landName($tL, $tLv);
          $tPoint = "({$tx}, {$ty})";

          // �h�q�{�ݔ���
          $defence = 0;
          if($defenceHex[$id][$tx][$ty] == 1) {
            $defence = 1;
          } elseif($defenceHex[$id][$tx][$ty] == -1) {
            $defence = 0;
          } else {
            if($tL == $init->landDefence) {
              // �h�q�{�݂ɖ���
              // �t���O���N���A
              for($i = 0; $i < 19; $i++) {
                $sx = $tx + $init->ax[$i];
                $sy = $ty + $init->ay[$i];

                // �s�ɂ��ʒu����
                if((($sy % 2) == 0) && (($ty % 2) == 1)) {
                  $sx--;
                }

                if(($sx < 0) || ($sx >= $init->islandSize) ||
                   ($sy < 0) || ($sy >= $init->islandSize)) {
                  // �͈͊O�̏ꍇ�������Ȃ�
                } else {
                  // �͈͓��̏ꍇ
                  $defenceHex[$id][$sx][$sy] = 0;
                }
              }
            } elseif(Turn::countAround($tLand, $tx, $ty, $init->landDefence, 19)) {
              $defenceHex[$id][$tx][$ty] = 1;
              $defence = 1;
            } else {
              $defenceHex[$id][$tx][$ty] = -1;
              $defence = 0;
            }
          }

          if($defence == 1) {
            // �󒆔��j
            if($kind == $init->comMissileST) {
              // �X�e���X
              $this->log->msCaughtS($id, $target, $name, $tName,$comName, $point, $tPoint);
            } else {
              // �ʏ�n
              $this->log->msCaught($id, $target, $name, $tName, $comName, $point, $tPoint);
            }
            continue;
          }

          // �u���ʂȂ��vhex���ŏ��ɔ���
          if((($tL == $init->landSea) && ($tLv == 0))|| // �[���C
             ((($tL == $init->landSea) ||    // �C�܂��́E�E�E
               ($tL == $init->landSbase) ||  // �C���n�܂��́E�E�E
               ($tL == $init->landMountain)) // �R�ŁE�E�E
              && ($kind != $init->comMissileLD))) { // ���j�e�ȊO
            // �C���n�̏ꍇ�A�C�̃t��
            if($tL == $init->landSbase) {
              $tL = $init->landSea;
            }
            $tLname = $this->landName($tL, $tLv);

            // ������
            if($kind == $init->comMissileST) {
              // �X�e���X
              $this->log->msNoDamageS($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
            } else {
              // �ʏ�n
              $this->log->msNoDamage($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
            }
            continue;
          }

          // �e�̎�ނŕ���
          if($kind == $init->comMissileLD) {
            // ���n�j��e
            switch($tL) {
            case $init->landMountain:
              // �R(�r�n�ɂȂ�)
              $this->log->msLDMountain($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
              // �r�n�ɂȂ�
              $tLand[$tx][$ty] = $init->landWaste;
              $tLandValue[$tx][$ty] = 0;
              continue 2;

            case $init->landSbase:
              // �C���n
              $this->log->msLDSbase($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
              break;
              
            case $init->landMonster:
              // ���b
              $this->log->msLDMonster($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
              break;
              
            case $init->landSea:
              // ��
              $this->log->msLDSea1($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
              break;
              
            default:
              // ���̑�
              $this->log->msLDLand($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
            }

            // �o���l
            if($tL == $init->landTown) {
              if(($land[$bx][$by] == $init->landBase) ||
                 ($land[$bx][$by] == $init->landSbase)) {
                // �܂���n�̏ꍇ�̂�
                $landValue[$bx][$by] += round($tLv / 20);
                if($landValue[$bx][$by] > $init->maxExpPoint) {
                  $landValue[$bx][$by] = $init->maxExpPoint;
                }
              }
            }

            // �󐣂ɂȂ�
            $tLand[$tx][$ty] = $init->landSea;
            $tIsland['area']--;
            $tLandValue[$tx][$ty] = 1;

            // �ł����c�A�󐣁A�C���n��������C
            if(($tL == $init->landOil) ||
               ($tL == $init->landSea) ||
               ($tL == $init->landSbase)) {
              $tLandValue[$tx][$ty] = 0;
            }
          } else {
            // ���̑��~�T�C��
            if($tL == $init->landWaste) {
              // �r�n(��Q�Ȃ�)
              if($kind == $init->comMissileST) {
                // �X�e���X
                $this->log->msWasteS($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
              } else {
                // �ʏ�
                $this->log->msWaste($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
              }
            } elseif($tL == $init->landMonster) {
              // ���b
              $monsSpec = Util::monsterSpec($tLv);
              $special = $init->monsterSpecial[$monsSpec['kind']];

              // �d����?
              if((($special == 3) && (($hako->islandTurn % 2) == 1)) ||
                 (($special == 4) && (($hako->islandTurn % 2) == 0))) {
                // �d����
                if($kind == $init->comMissileST) {
                  // �X�e���X
                  $this->log->msMonNoDamageS($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
                } else {
                  // �ʏ�e
                  $this->log->msMonNoDamage($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
                }
                continue;
              } else {
                // �d��������Ȃ�
                if($monsSpec['hp'] == 1) {
                  // ���b���Ƃ߂�
                  if(($land[$bx][$by] == $init->landBase) ||
                     ($land[$bx][$by] == $init->landSbase)) {
                    // �o���l
                    $landValue[$bx][$by] += $init->monsterExp[$monsSpec['kind']];
                    if($landValue[$bx][$by] > $init->maxExpPoint) {
                      $landValue[$bx][$by] = $init->maxExpPoint;
                    }
                  }

                  if($kind == $init->comMissileST) {
                    // �X�e���X
                    $this->log->msMonKillS($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
                  } else {
                    // �ʏ�
                    $this->log->msMonKill($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
                  }

                  // ����
                  $value = $init->monsterValue[$monsSpec['kind']];
                  if($value > 0) {
                    $tIsland['money'] += $value;
                    $this->log->msMonMoney($target, $tLname, $value);
                  }

                  // �܊֌W
//                  $prize = $island['prize'];
                  list($flags, $monsters, $turns) = split(",", $prize, 3);
                  $v = 1 << $monsSpec['kind'];
                  $monsters |= $v;

                  $prize = "{$flags},{$monsters},{$turns}";
//                  $island['prize'] = "{$flags},{$monsters},{$turns}";
                } else {
                  // ���b�����Ă�
                  if($kind == $init->comMissileST) {
                    // �X�e���X
                    $this->log->msMonsterS($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
                  } else {
                    // �ʏ�
                    $this->log->msMonster($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
                  }
                  // HP��1����
                  $tLandValue[$tx][$ty]--;
                  continue;
                }

              }
            } else {
              // �ʏ�n�`
              if($kind == $init->comMissileST) {
                // �X�e���X
                $this->log->msNormalS($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
              } else {
                // �ʏ�
                $this->log->msNormal($id, $target, $name, $tName, $comName, $tLname, $point, $tPoint);
              }
            }
            // �o���l
            if($tL == $init->landTown) {
              if(($land[$bx][$by] == $init->landBase) ||
                 ($land[$bx][$by] == $init->landSbase)) {
                $landValue[$bx][$by] += round($tLv / 20);
                $boat += $tLv; // �ʏ�~�T�C���Ȃ̂œ�Ƀv���X
                if($landValue[$bx][$by] > $init->maxExpPoint) {
                  $landValue[$bx][$by] = $init->maxExpPoint;
                }
              }
            }

            // �r�n�ɂȂ�
            $tLand[$tx][$ty] = $init->landWaste;
            $tLandValue[$tx][$ty] = 1; // ���e�_
            // �ł����c��������C
            if($tL == $init->landOil) {
              $tLand[$tx][$ty] = $init->landSea;
              $tLandValue[$tx][$ty] = 0;
            }
          }
        }

        // �J�E���g���₵�Ƃ�
        $count++;
      }


      if($flag == 0) {
        // ��n��������������ꍇ
        $this->log->msNoBase($id, $name, $comName);

        $returnMode = 0;
        break;
      }
      
      $tIsland['land'] = $tLand;
      $tIsland['landValue'] = $tLandValue;
      unset($hako->islands[$tn]);
      $hako->islands[$tn] = $tIsland;
      
      
      // �����
      $boat = round($boat / 2);
      if(($boat > 0) && ($id != $target) && ($kind != $init->comMissileST)) {
        // ��Y��
        $achive = 0; // ���B�
        for($i = 0; ($i < $init->pointNumber && $boat > 0); $i++) {
          $bx = $this->rpx[$i];
          $by = $this->rpy[$i];
          if($land[$bx][$by] == $init->landTown) {
            // ���̏ꍇ
            $lv = $landValue[$bx][$by];
            if($boat > 50) {
              $lv += 50;
              $boat -= 50;
              $achive += 50;
            } else {
              $lv += $boat;
              $achive += $boat;
              $boat = 0;
            }
            if($lv > 200) {
              $boat += ($lv - 200);
              $achive -= ($lv - 200);
              $lv = 200;
            }
            $landValue[$bx][$by] = $lv;
          } elseif($land[$bx][$by] == $init->landPlains) {
            // ���n�̏ꍇ
            $land[$bx][$by] = $init->landTown;;
            if($boat > 10) {
              $landValue[$bx][$by] = 5;
              $boat -= 10;
              $achive += 10;
            } elseif($boat > 5) {
              $landValue[$bx][$by] = $boat - 5;
              $achive += $boat;
              $boat = 0;
            }
          }
          if($boat <= 0) {
            break;
          }
        }
        if($achive > 0) {
          // �����ł����������ꍇ�A���O��f��
          $this->log->msBoatPeople($id, $name, $achive);

          // ��̐�����萔�ȏ�Ȃ�A���a�܂̉\������
          if($achive >= 200) {
            $prize = $island['prize'];
            list($flags, $monsters, $turns) = split(",", $prize, 3);

            if((!($flags & 8)) &&  $achive >= 200){
              $flags |= 8;
              $this->log->prize($id, $name, $init->prizeName[4]);
            } elseif((!($flags & 16)) &&  $achive > 500){
              $flags |= 16;
              $this->log->prize($id, $name, $init->prizeName[5]);
            } elseif((!($flags & 32)) &&  $achive > 800){
              $flags |= 32;
              $this->log->prize($id, $name, $init->prizeName[6]);
            }
            $island['prize'] = "{$flags},{$monsters},{$turns}";
          }
        }
      }
      
      $returnMode = 1;
      break;

    case $init->comSendMonster:
      // ���b�h��
      // �^�[�Q�b�g�擾
      $tn = $hako->idToNumber[$target];
      $tIsland = $hako->islands[$tn];
      $tName = $tIsland['name'];
      
      if($tn != 0 && empty($tn)) {
        // �^�[�Q�b�g�����łɂȂ�
        $this->log->msNoTarget($id, $name, $comName);

        $returnMode = 0;
        break;
      }

      // ���b�Z�[�W
      $this->log->monsSend($id, $target, $name, $tName);
      $tIsland['monstersend']++;
      $hako->islands[$tn] = $tIsland;

      $island['money'] -= $cost;
      $returnMode = 1;
      break;
    case $init->comSell:
      // �A�o�ʌ���
      if($arg == 0) { $arg = 1; }
      $value = min($arg * (-$cost), $island['food']);

      // �A�o���O
      $this->log->sell($id, $name, $comName, $value);
      $island['food'] -=  $value;
      $island['money'] += ($value / 10);

      $returnMode = 0;
      break;
      
    case $init->comFood:
    case $init->comMoney:
      // �����n
      // �^�[�Q�b�g�擾
      $tn = $hako->idToNumber[$target];
      $tIsland = &$hako->islands[$tn];
      $tName = $tIsland['name'];

      // �����ʌ���
      if($arg == 0) { $arg = 1; }

      if($cost < 0) {
        $value = min($arg * (-$cost), $island['food']);
        $str = "{$value}{$init->unitFood}";
      } else {
        $value = min($arg * ($cost), $island['money']);
        $str = "{$value}{$init->unitMoney}";
      }

      // �������O
      $this->log->aid($id, $target, $name, $tName, $comName, $str);

      if($cost < 0) {
        $island['food'] -= $value;
        $tIsland['food'] += $value;
      } else {
        $island['money'] -= $value;
        $tIsland['money'] += $value;
      }
      $hako->islands[$tn] = $tIsland;

      $returnMode = 0;
      break;
      
    case $init->comPropaganda:
      // �U�v����
      $this->log->propaganda($id, $name, $comName);
      $island['propaganda'] = 1;
      $island['money'] -= $cost;

      $returnMode = 1;
      break;

    case $init->comGiveup:
      // ����
      $this->log->giveup($id, $name);
      $island['dead'] = 1;
      unlink("{$init->dirName}/island.{$id}");

      $returnMode = 1;
      break;
    }

    // �ύX���ꂽ�\���̂���ϐ��������߂�
//    $hako->islands[$hako->idToNumber[$id]] = $island;

    // ���㏈��
    unset($island['prize']);
    unset($island['land']);
    unset($island['landValue']);
    unset($island['command']);
    $island['prize'] = $prize;
    $island['land'] = $land;
    $island['landValue'] = $landValue;
    $island['command'] = $comArray;

    return $returnMode;
  }
  //---------------------------------------------------
  // ��������ђP�w�b�N�X�ЊQ
  //---------------------------------------------------
  function doEachHex($hako, &$island) {
    global $init;
    // ���o�l
    $name = $island['name'];
    $id = $island['id'];
    $land = $island['land'];
    $landValue = $island['landValue'];

    // ������l���̃^�l�l
    $addpop  = 10;  // ���A��
    $addpop2 = 0; // �s�s
    if($island['food'] < 0) {
      // �H���s��
      $addpop = -30;
    } elseif($island['propaganda'] == 1) {
      // �U�v������
      $addpop = 30;
      $addpop2 = 3;
    }
    $monsterMove = array();
    // ���[�v
    for($i = 0; $i < $init->pointNumber; $i++) {
      $x = $this->rpx[$i];
      $y = $this->rpy[$i];
      $landKind = $land[$x][$y];
      $lv = $landValue[$x][$y];

      switch($landKind) {
      case $init->landTown:
        // ���n
        if($addpop < 0) {
          // �s��
          $lv -= (Util::random(-$addpop) + 1);
          if($lv <= 0) {
            // ���n�ɖ߂�
            $land[$x][$y] = $init->landPlains;
            $landValue[$x][$y] = 0;
            continue;
          }
        } else {
          // ����
          if($lv < 100) {
            $lv += Util::random($addpop) + 1;
            if($lv > 100) {
              $lv = 100;
            }
          } else {
            // �s�s�ɂȂ�Ɛ����x��
            if($addpop2 > 0) {
              $lv += Util::random($addpop2) + 1;
            }
          }
        }
        if($lv > 200) {
          $lv = 200;
        }
        $landValue[$x][$y] = $lv;
        break;
        
      case $init->landPlains:
        // ���n
        if(Util::random(5) == 0) {
          // ����ɔ_��A��������΁A���������ɂȂ�
          if($this->countGrow($land, $landValue, $x, $y)){
            $land[$x][$y] = $init->landTown;
            $landValue[$x][$y] = 1;
          }
        }
        break;
        
      case $init->landForest:
        // �X
        if($lv < 200) {
          // �؂𑝂₷
          $landValue[$x][$y]++;
        }
        break;
        
      case $init->landDefence:
        if($lv == 1) {
          // �h�q�{�ݎ���
          $lName = $this->landName($landKind, $lv);
          $this->log->bombFire($id, $name, $lName, "($x, $y)");

          // �L���Q���[�`��
          $this->wideDamage($id, $name, &$land, &$landValue, $x, $y);
        }
        break;
        
      case $init->landOil:
        // �C����c
        $lName = $this->landName($landKind, $lv);
        $value = $init->oilMoney;
        $island['money'] += $value;
        $str = "{$value}{$init->unitMoney}";

        // �������O
        $this->log->oilMoney($id, $name, $lName, "($x, $y)", $str);

        // �͊�����
        if(Util::random(1000) < $init->oilRatio) {
          // �͊�
          $this->log->oilEnd($id, $name, $lName, "($x, $y)");
          $land[$x][$y] = $init->landSea;
          $landValue[$x][$y] = 0;
        }
        break;
        
      case $init->landMonster:

        // ���b
        if($monsterMove[$x][$y] == 2) {
          // ���łɓ�������
          break;
        }

        // �e�v�f�̎��o��
        $monsSpec = Util::monsterSpec($landValue[$x][$y]);
        $special  = $init->monsterSpecial[$monsSpec['kind']];
        $mName    = $monsSpec['name'];
        // �d����?
        if((($special == 3) && (($hako->islandTurn % 2) == 1)) ||
           (($special == 4) && (($hako->islandTurn % 2) == 0))) {
          // �d����
          break;
        }

        // ��������������
        for($j = 0; $j < 3; $j++) {
          $d = Util::random(6) + 1;
          $sx = $x + $init->ax[$d];
          $sy = $y + $init->ay[$d];

          // �s�ɂ��ʒu����
          if((($sy % 2) == 0) && (($y % 2) == 1)) {
            $sx--;
          }

          // �͈͊O����
          if(($sx < 0) || ($sx >= $init->islandSize) ||
             ($sy < 0) || ($sy >= $init->islandSize)) {
            continue;
          }
          // �C�A�C��A���c�A���b�A�R�A�L�O��ȊO
          if(($land[$sx][$sy] != $init->landSea) &&
             ($land[$sx][$sy] != $init->landSbase) &&
             ($land[$sx][$sy] != $init->landOil) &&
             ($land[$sx][$sy] != $init->landMountain) &&
             ($land[$sx][$sy] != $init->landMonument) &&
             ($land[$sx][$sy] != $init->landMonster)) {
            break;
          }
        }

        if($j == 3) {
          // �����Ȃ�����
          break;
        }

        // ��������̒n�`�ɂ�胁�b�Z�[�W
        $l = $land[$sx][$sy];
        $lv = $landValue[$sx][$sy];
        $lName = $this->landName($l, $lv);
        $point = "({$sx}, {$sy})";

        // �ړ�
        $land[$sx][$sy] = $land[$x][$y];
        $landValue[$sx][$sy] = $landValue[$x][$y];

        // ���Ƌ����ʒu���r�n��
        $land[$x][$y] = $init->landWaste;
        $landValue[$x][$y] = 0;
      
        // �ړ��ς݃t���O
        if($init->monsterSpecial[$monsSpec['kind']] == 2) {
          // �ړ��ς݃t���O�͗��ĂȂ�
        } elseif($init->monsterSpecial[$monsSpec['kind']] == 1) {
          // �������b
          $monsterMove[$sx][$sy] = $monsterMove[$x][$y] + 1;
        } else {
          // ���ʂ̉��b
          $monsterMove[$sx][$sy] = 2;
        }
        if(($l == $init->landDefence) && ($init->dBaseAuto == 1)) {
          // �h�q�{�݂𓥂�
          $this->log->monsMoveDefence($id, $name, $lName, $point, $mName);

          // �L���Q���[�`��
          $this->wideDamage($id, $name, &$land, &$landValue, $sx, $sy);
        } else {
          // �s���悪�r�n�ɂȂ�
          $this->log->monsMove($id, $name, $lName, $point, $mName);
        }
        break;
      }
      // ���ł�$init->landTown��case���Ŏg���Ă���̂�switch��ʂɗp��
      switch($landKind) {
      case $init->landTown:
      case $init->landHaribote:
      case $init->landFactory:
        // �΍Д���
        if($landKind == $init->landTown && ($lv <= 30))
          break;
        
        if(Util::random(1000) < $init->disFire) {
          // ���͂̐X�ƋL�O��𐔂���
          if((Turn::countAround($land, $x, $y, $init->landForest, 7) +
              Turn::countAround($land, $x, $y, $init->landMonument, 7)) == 0) {
            // ���������ꍇ�A�΍Ђŉ��
            $l = $land[$x][$y];
            $lv = $landValue[$x][$y];
            $point = "({$x}, {$y})";
            $lName = $this->landName($l, $lv);
            $this->log->fire($id, $name, $lName, $point);
            $land[$x][$y] = $init->landWaste;
            $landValue[$x][$y] = 0;
          }
        }
        break;
      }
    }
    // �ύX���ꂽ�\���̂���ϐ��������߂�
    $island['land'] = $land;
    $island['landValue'] = $landValue;
  }

  //---------------------------------------------------
  // ���S��
  //---------------------------------------------------
  function doIslandProcess($hako, &$island) {
    global $init;
    
    // ���o�l
    $name = $island['name'];
    $id = $island['id'];
    $land = $island['land'];
    $landValue = $island['landValue'];

    // �n�k����
    if(Util::random(1000) < (($island['prepare2'] + 1) * $init->disEarthquake)) {
      // �n�k����
      $this->log->earthquake($id, $name);

      for($i = 0; $i < $init->pointNumber; $i++) {
        $x = $this->rpx[$i];
        $y = $this->rpy[$i];
        $landKind = $land[$x][$y];
        $lv = $landValue[$x][$y];

        if((($landKind == $init->landTown) && ($lv >= 100)) ||
           ($landKind == $init->landHaribote) ||
           ($landKind == $init->landFactory)) {
          // 1/4�ŉ��
          if(Util::random(4) == 0) {
            $this->log->eQDamage($id, $name, $this->landName($landKind, $lv), "({$x}, {$y})");
            $land[$x][$y] = $init->landWaste;
            $landValue[$x][$y] = 0;
          }
        }
      }
    }

    // �H���s��
    if($island['food'] <= 0) {
      // �s�����b�Z�[�W
      $this->log->starve($id, $name);
      $island['food'] = 0;

      for($i = 0; $i < $init->pointNumber; $i++) {
        $x = $this->rpx[$i];
        $y = $this->rpy[$i];
        $landKind = $land[$x][$y];
        $lv = $landValue[$x][$y];

        if(($landKind == $init->landFarm) ||
           ($landKind == $init->landFactory) ||
           ($landKind == $init->landBase) ||
           ($landKind == $init->landDefence)) {
          // 1/4�ŉ��
          if(Util::random(4) == 0) {
            $this->log->svDamage($id, $name, $this->landName($landKind, $lv), "({$x}, {$y})");
            $land[$x][$y] = $init->landWaste;
            $landValue[$x][$y] = 0;
          }
        }
      }
    }

    // �Ôg����
    if(Util::random(1000) < $init->disTsunami) {
      // �Ôg����
      $this->log->tsunami($id, $name);

      for($i = 0; $i < $init->pointNumber; $i++) {
        $x = $this->rpx[$i];
        $y = $this->rpy[$i];
        $landKind = $land[$x][$y];
        $lv = $landValue[$x][$y];

        if(($landKind == $init->landTown) ||
           ($landKind == $init->landFarm) ||
           ($landKind == $init->landFactory) ||
           ($landKind == $init->landBase) ||
           ($landKind == $init->landDefence) ||
           ($landKind == $init->landHaribote)) {
          // 1d12 <= (���͂̊C - 1) �ŕ���
          if(Util::random(12) <
             (Turn::countAround($land, $x, $y, $init->landOil, 7) +
              Turn::countAround($land, $x, $y, $init->landSbase, 7) +
              Turn::countAround($land, $x, $y, $init->landSea, 7) - 1)) {
            $this->log->tsunamiDamage($id, $name, $this->landName($landKind, $lv), "({$x}, {$y})");
            $land[$x][$y] = $init->landWaste;
            $landValue[$x][$y] = 0;
          }
        }

      }
    }

    // ���b����
    $r = Util::random(10000);
    $pop = $island['pop'];
    do{
      if((($r < ($init->disMonster * $island['area'])) &&
          ($pop >= $init->disMonsBorder1)) ||
         ($island['monstersend'] > 0)) {
        // ���b�o��
        // ��ނ����߂�
        if($island['monstersend'] > 0) {
          // �l��
          $kind = 0;
          $island['monstersend']--;
        } elseif($pop >= $init->disMonsBorder3) {
          // level3�܂�
          $kind = Util::random($init->monsterLevel3) + 1;
        } elseif($pop >= $init->disMonsBorder2) {
          // level2�܂�
          $kind = Util::random($init->monsterLevel2) + 1;
        } else {
          // level1�̂�
          $kind = Util::random($init->monsterLevel1) + 1;
        }

        // lv�̒l�����߂�
        $lv = $kind * 10
          + $init->monsterBHP[$kind] + Util::random($init->monsterDHP[$kind]);

        // �ǂ��Ɍ���邩���߂�
        for($i = 0; $i < $init->pointNumber; $i++) {
          $bx = $this->rpx[$i];
          $by = $this->rpy[$i];
          if($land[$bx][$by] == $init->landTown) {

            // �n�`��
            $lName = $this->landName($init->landTown, $landValue[$bx][$by]);

            // ���̃w�b�N�X�����b��
            $land[$bx][$by] = $init->landMonster;
            $landValue[$bx][$by] = $lv;

            // ���b���
            $monsSpec = Util::monsterSpec($lv);
            $mName    = $monsSpec['name'];

            // ���b�Z�[�W
            $this->log->monsCome($id, $name, $mName, "({$bx}, {$by})", $lName);
            break;
          }
        }
      }
    } while($island['monstersend'] > 0);

    // �n�Ւ�������
    if(($island['area'] > $init->disFallBorder) &&
       (Util::random(1000) < $init->disFalldown)) {
      // �n�Ւ�������
      $this->log->falldown($id, $name);

      for($i = 0; $i < $init->pointNumber; $i++) {
        $x = $this->rpx[$i];
        $y = $this->rpy[$i];
        $landKind = $land[$x][$y];
        $lv = $landValue[$x][$y];

        if(($landKind != $init->landSea) &&
           ($landKind != $init->landSbase) &&
           ($landKind != $init->landOil) &&
           ($landKind != $init->landMountain)) {

          // ���͂ɊC������΁A�l��-1��
          if(Turn::countAround($land, $x, $y, $init->landSea, 7) +
             Turn::countAround($land, $x, $y, $init->landSbase, 7)) {
            $this->log->falldownLand($id, $name, $this->landName($landKind, $lv), "({$x}, {$y})");
            $land[$x][$y] = -1;
            $landValue[$x][$y] = 0;
          }
        }
      }

      for($i = 0; $i < $init->pointNumber; $i++) {
        $x = $this->rpx[$i];
        $y = $this->rpy[$i];
        $landKind = $land[$x][$y];

        if($landKind == -1) {
          // -1�ɂȂ��Ă��鏊��󐣂�
          $land[$x][$y] = $init->landSea;
          $landValue[$x][$y] = 1;
        } elseif ($landKind == $init->landSea) {
          // �󐣂͊C��
          $landValue[$x][$y] = 0;
        }

      }
    }

    // �䕗����
    if(Util::random(1000) < $init->disTyphoon) {
      // �䕗����
      $this->log->typhoon($id, $name);

      for($i = 0; $i < $init->pointNumber; $i++) {
        $x = $this->rpx[$i];
        $y = $this->rpy[$i];
        $landKind = $land[$x][$y];
        $lv = $landValue[$x][$y];

        if(($landKind == $init->landFarm) ||
           ($landKind == $init->landHaribote)) {

          // 1d12 <= (6 - ���͂̐X) �ŕ���
          if(Util::random(12) <
             (6
              - Turn::countAround($land, $x, $y, $init->landForest, 7)
              - Turn::countAround($land, $x, $y, $init->landMonument, 7))) {
            $this->log->typhoonDamage($id, $name, $this->landName($landKind, $lv), "({$x}, {$y})");
            $land[$x][$y] = $init->landPlains;
            $landValue[$x][$y] = 0;
          }
        }
      }
    }

    // ����覐Δ���
    if(Util::random(1000) < $init->disHugeMeteo) {

      // ����
      $x = Util::random($init->islandSize);
      $y = Util::random($init->islandSize);
      $landKind = $land[$x][$y];
      $lv = $landValue[$x][$y];
      $point = "({$x}, {$y})";

      // ���b�Z�[�W
      $this->log->hugeMeteo($id, $name, $point);

      // �L���Q���[�`��
      $this->wideDamage($id, $name, &$land, &$landValue, $x, $y);
    }

    // ����~�T�C������
    while($island['bigmissile'] > 0) {
      $island['bigmissile']--;

      // ����
      $x = Util::random($init->islandSize);
      $y = Util::random($init->islandSize);
      $landKind = $land[$x][$y];
      $lv = $landValue[$x][$y];
      $point = "({$x}, {$y})";

      // ���b�Z�[�W
      $this->log->monDamage($id, $name, $point);

      // �L���Q���[�`��
      $this->wideDamage($id, $name, &$land, &$landValue, $x, $y);
    }

    // 覐Δ���
    if(Util::random(1000) < $init->disMeteo) {
      $first = 1;
      while((Util::random(2) == 0) || ($first == 1)) {
        $first = 0;

        // ����
        $x = Util::random($init->islandSize);
        $y = Util::random($init->islandSize);
        $landKind = $land[$x][$y];
        $lv = $landValue[$x][$y];
        $point = "({$x}, {$y})";

        if(($landKind == $init->landSea) && ($lv == 0)){
          // �C�|�`��
          $this->log->meteoSea($id, $name, $this->landName($landKind, $lv), $point);
        } elseif($landKind == $init->landMountain) {
          // �R�j��
          $this->log->meteoMountain($id, $name, $this->landName($landKind, $lv), $point);
          $land[$x][$y] = $init->landWaste;
          $landValue[$x][$y] = 0;
          continue;
        } elseif($landKind == $init->landSbase) {
          $this->log->meteoSbase($id, $name, $this->landName($landKind, $lv), $point);
        } elseif($landKind == $init->landMonster) {
          $this->log->meteoMonster($id, $name, $this->landName($landKind, $lv), $point);
        } elseif($landKind == $init->landSea) {
          // ��
          $this->log->meteoSea1($id, $name, $this->landName($landKind, $lv), $point);
        } else {
          $this->log->meteoNormal($id, $name, $this->landName($landKind, $lv), $point);
        }
        $land[$x][$y] = $init->landSea;
        $landValue[$x][$y] = 0;
      }
    }

    // ���Δ���
    if(Util::random(1000) < $init->disEruption) {
      $x = Util::random($init->islandSize);
      $y = Util::random($init->islandSize);
      $landKind = $land[$x][$y];
      $lv = $landValue[$x][$y];
      $point = "({$x}, {$y})";
      $this->log->eruption($id, $name, $this->landName($landKind, $lv), $point);
      $land[$x][$y] = $init->landMountain;
      $landValue[$x][$y] = 0;

      for($i = 1; $i < 7; $i++) {
        $sx = $x + $init->ax[$i];
        $sy = $y + $init->ay[$i];

        // �s�ɂ��ʒu����
        if((($sy % 2) == 0) && (($y % 2) == 1)) {
          $sx--;
        }

        $landKind = $land[$sx][$sy];
        $lv = $landValue[$sx][$sy];
        $point = "({$sx}, {$sy})";

        if(($sx < 0) || ($sx >= $init->islandSize) ||
           ($sy < 0) || ($sy >= $init->islandSize)) {
        } else {
          // �͈͓��̏ꍇ
          $landKind = $land[$sx][$sy];
          $lv = $landValue[$sx][$sy];
          $point = "({$sx}, {$sy})";
          if(($landKind == $init->landSea) ||
             ($landKind == $init->landOil) ||
             ($landKind == $init->landSbase)) {
            // �C�̏ꍇ
            if($lv == 1) {
              // ��
              $this->log->eruptionSea1($id, $name, $this->landName($landKind, $lv), $point);
            } else {
              $this->log->eruptionSea($id, $name, $this->landName($landKind, $lv), $point);
              $land[$sx][$sy] = $init->landSea;
              $landValue[$sx][$sy] = 1;
              continue;
            }
          } elseif(($landKind == $init->landMountain) ||
                  ($landKind == $init->landMonster) ||
                  ($landKind == $init->landWaste)) {
            continue;
          } else {
            // ����ȊO�̏ꍇ
            $this->log->eruptionNormal($id, $name, $this->landName($landKind, $lv), $point);
          }
          $land[$sx][$sy] = $init->landWaste;
          $landValue[$sx][$sy] = 0;
        }
      }
    }
    // �ύX���ꂽ�\���̂���ϐ��������߂�
    $island['land'] = $land;
    $island['landValue'] = $landValue;

    // �H�������ӂ�Ă��犷��
    if($island['food'] > $init->maxFood) {
      $island['money'] += round(($island['food'] - $init->maxFood) / 10);
      $island['food'] = $init->maxFood;
    }

    // �������ӂ�Ă���؂�̂�
    if($island['money'] > $init->maxMoney) {
      $island['money'] = $init->maxMoney;
    }

    // �e��̒l���v�Z
    Turn::estimate($island);

    // �ɉh�A�Г��
    $pop = $island['pop'];
    $damage = $island['oldPop'] - $pop;
    $prize = $island['prize'];
    list($flags, $monsters, $turns) = split(",", $prize, 3);


    // �ɉh��
    if((!($flags & 1)) &&  $pop >= 3000){
      $flags |= 1;
      $this->log->prize($id, $name, $init->prizeName[1]);
    } elseif((!($flags & 2)) &&  $pop >= 5000){
      $flags |= 2;
      $this->log->prize($id, $name, $init->prizeName[2]);
    } elseif((!($flags & 4)) &&  $pop >= 10000){
      $flags |= 4;
      $this->log->prize($id, $name, $init->prizeName[3]);
    }

    // �Г��
    if((!($flags & 64)) &&  $damage >= 500){
      $flags |= 64;
      $this->log->prize($id, $name, $init->prizeName[7]);
    } elseif((!($flags & 128)) &&  $damage >= 1000){
      $flags |= 128;
      $this->log->prize($id, $name, $init->prizeName[8]);
    } elseif((!($flags & 256)) &&  $damage >= 2000){
      $flags |= 256;
      $this->log->prize($id, $name, $init->prizeName[9]);
    }

    $island['prize'] = "{$flags},{$monsters},{$turns}";

  }

  //---------------------------------------------------
  // ���͂̒��A�_�ꂪ���邩����
  //---------------------------------------------------
  function countGrow($land, $landValue, $x, $y) {
    global $init;

    for($i = 1; $i < 7; $i++) {
      $sx = $x + $init->ax[$i];
      $sy = $y + $init->ay[$i];

      // �s�ɂ��ʒu����
      if((($sy % 2) == 0) && (($y % 2) == 1)) {
        $sx--;
      }

      if(($sx < 0) || ($sx >= $init->islandSize) ||
         ($sy < 0) || ($sy >= $init->islandSize)) {
      } else {
        // �͈͓��̏ꍇ
        if(($land[$sx][$sy] == $init->landTown) ||
           ($land[$sx][$sy] == $init->landFarm)) {
          if($landValue[$sx][$sy] != 1) {
            return true;
          }
        }
      }
    }
    return false;
  }
  //---------------------------------------------------
  // �L���Q���[�`��
  //---------------------------------------------------
  function wideDamage($id, $name, $land, $landValue, $x, $y) {
    global $init;

    for($i = 0; $i < 19; $i++) {
      $sx = $x + $init->ax[$i];
      $sy = $y + $init->ay[$i];

      // �s�ɂ��ʒu����
      if((($sy % 2) == 0) && (($y % 2) == 1)) {
        $sx--;
      }

      $landKind = $land[$sx][$sy];
      $lv = $landValue[$sx][$sy];
      $landName = $this->landName($landKind, $lv);
      $point = "({$sx}, {$sy})";

      // �͈͊O����
      if(($sx < 0) || ($sx >= $init->islandSize) ||
         ($sy < 0) || ($sy >= $init->islandSize)) {
        continue;
      }

      // �͈͂ɂ�镪��
      if($i < 7) {
        // ���S�A�����1�w�b�N�X
        if($landKind == $init->landSea) {
          $landValue[$sx][$sy] = 0;
          continue;
        } elseif(($landKind == $init->landSbase) ||
                 ($landKind == $init->landOil)) {
          $this->log->wideDamageSea2($id, $name, $landName, $point);
          $land[$sx][$sy] = $init->landSea;
          $landValue[$sx][$sy] = 0;
        } else {
          if($landKind == $init->landMonster) {
            $this->log->wideDamageMonsterSea($id, $name, $landName, $point);
          } else {
            $this->log->wideDamageSea($id, $name, $landName, $point);
          }
          $land[$sx][$sy] = $init->landSea;
          if($i == 0) {
            // �C
            $landValue[$sx][$sy] = 0;
          } else {
            // ��
            $landValue[$sx][$sy] = 1;
          }
        }
      } else {
        // 2�w�b�N�X
        if(($landKind == $init->landSea) ||
           ($landKind == $init->landOil) ||
           ($landKind == $init->landWaste) ||
           ($landKind == $init->landMountain) ||
           ($landKind == $init->landSbase)) {
          continue;
        } elseif($landKind == $init->landMonster) {
          $this->log->wideDamageMonster($id, $name, $landName, $point);
          $land[$sx][$sy] = $init->landWaste;
          $landValue[$sx][$sy] = 0;
        } else {
          $this->log->wideDamageWaste($id, $name, $landName, $point);
          $land[$sx][$sy] = $init->landWaste;
          $landValue[$sx][$sy] = 0;
        }
      }
    }
  }

  //---------------------------------------------------
  // �l�����Ń\�[�g
  //---------------------------------------------------
  function islandSort(&$hako) {
    global $init;
    usort($hako->islands, 'popComp');
  }
  //---------------------------------------------------
  // �����A����t�F�C�Y
  //---------------------------------------------------
  function income(&$island) {
    global $init;
    
    $pop = $island['pop'];
    $farm = $island['farm'] * 10;
    $factory = $island['factory'];
    $mountain =$island['mountain'];

    // ����
    if($pop > $farm) {
      // �_�Ƃ�������肪�]��ꍇ
      $island['food'] += $farm; // �_��t���ғ�
      $island['money'] +=
        min(round(($pop - $farm) / 10),
              $factory + $mountain);
    } else {
      // �_�Ƃ����Ŏ��t�̏ꍇ
      $island['food'] += $pop; // �S����ǎd��
    }

    // �H������
    $island['food'] = round($island['food'] - $pop * $init->eatenFood);
  }
  //---------------------------------------------------
  // �l�����̑��̒l���Z�o
  //---------------------------------------------------
  function estimate(&$island) {
    // estimate(&$island) �̂悤�Ɏg�p
    
    global $init;
    $land = $island['land'];
    $landValue = $island['landValue'];

    $area       = 0;
    $pop        = 0;
    $farm       = 0;
    $factory    = 0;
    $mountain   = 0;
    $monster    = 0;
    // ������
    for($y = 0; $y < $init->islandSize; $y++) {
      for($x = 0; $x < $init->islandSize; $x++) {
        $kind = $land[$x][$y];
        $value = $landValue[$x][$y];
        if(($kind != $init->landSea) &&
           ($kind != $init->landSbase) &&
           ($kind != $init->landOil)){
          $area++;
          switch($kind) {
          case $init->landTown:
            // ��
            $pop += $value;
            break;
          case $init->landFarm:
            // �_��
            $farm += $value;
            break;
          case $init->landFactory:
            // �H��
            $factory += $value;
            break;
          case $init->landMountain:
            // �R
            $mountain += $value;
            break;
          case $init->landMonster:
            // ���b
            $monster++;
            break;
          }
        }
      }
    }
    // ���
    $island['pop']      = $pop;
    $island['area']     = $area;
    $island['farm']     = $farm;
    $island['factory']  = $factory;
    $island['mountain'] = $mountain;
    $island['monster'] = $monster;
  }
  //---------------------------------------------------
  // �͈͓��̒n�`�𐔂���
  //---------------------------------------------------
  function countAround($land, $x, $y, $kind, $range) {
    global $init;
    // �͈͓��̒n�`�𐔂���
    $count = 0;
    for($i = 0; $i < $range; $i++) {
      $sx = $x + $init->ax[$i];
      $sy = $y + $init->ay[$i];

      // �s�ɂ��ʒu����
      if((($sy % 2) == 0) && (($y % 2) == 1)) {
        $sx--;
      }

      if(($sx < 0) || ($sx >= $init->islandSize) ||
         ($sy < 0) || ($sy >= $init->islandSize)) {
        // �͈͊O�̏ꍇ
        if($kind == $init->landSea) {
          // �C�Ȃ���Z
          $count++;
        }
      } else {
        // �͈͓��̏ꍇ
        if($land[$sx][$sy] == $kind) {
          $count++;
        }
      }
    }
    return $count;
  }
  //---------------------------------------------------
  // �n�`�̌Ăѕ�
  //---------------------------------------------------
  function landName($land, $lv) {
    global $init;
    switch($land) {
    case $init->landSea:
      if($lv == 1) {
        return '��';
      } else {
        return '�C';
      }
      break;
    case $init->landWaste:
      return '�r�n';
    case $init->landPlains:
      return '���n';
    case $init->landTown:
      if($lv < 30) {
        return '��';
      } elseif($lv < 100) {
        return '��';
      } else {
        return '�s�s';
      }
    case $init->landForest:
      return '�X';
    case $init->landFarm:
      return '�_��';
    case $init->landFactory:
      return '�H��';
    case $init->landBase:
      return '�~�T�C����n';
    case $init->landDefence:
      return '�h�q�{��';
    case $init->landMountain:
      return '�R';
    case $init->landMonster:
      $monsSpec = Util::monsterSpec($lv);
      return $monsSpec['name'];
    case $init->landSbase:
      return '�C���n';
    case $init->landOil:
      return '�C����c';
    case $init->landMonument:
      return $init->monumentName[$lv];
    case $init->landHaribote:
      return '�n���{�e';

    }
  }
}
// �l�����r
function popComp($x, $y) {
  if($x['pop'] == $y['pop']) return 0;
  return ($x['pop'] > $y['pop']) ? -1 : 1;
}


?>