<?php
/*******************************************************************

  ���돔���Q for PHP

  
  $Id: hako-html.php,v 1.12 2004/08/10 22:00:41 Watson Exp $

*******************************************************************/

if(GZIP == true) {
  // gzip���k�]���p
  require_once "HTTP/Compress.php";
  $http = new HTTP_Compress;
}

//--------------------------------------------------------------------
class HTML {

  //---------------------------------------------------
  // HTML �w�b�_�o��
  //---------------------------------------------------
  function header($data = "") {
    global $init;
    global $PRODUCT_VERSION;

    // ���k�]��
    if(GZIP == true) {
      global $http;
      $http->start();
    }
    header("X-Product-Version: {$PRODUCT_VERSION}");
    $css = (empty($data['defaultSkin'])) ? $init->cssList[0] : $data['defaultSkin'];
    print <<<END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
<base href="{$init->imgDir}/">
<meta http-equiv="Content-type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link rel="stylesheet" type="text/css" href="{$init->cssDir}/{$css}">
<title>{$init->title}</title>
<script type="text/javascript" src="{$init->baseDir}/hako.js"></script>
</head>
<body>

<div id="LinkHeader">
<a href="http://www.bekkoame.ne.jp/~tokuoka/hakoniwa.html">���돔���X�N���v�g�z�z��</a>
<a href="http://scrlab.g-7.ne.jp/">[PHP]</a>�@
[<a href="{$GLOBALS['THIS_FILE']}?mode=conf">���̓o�^�E�ݒ�ύX</a>]
</div>
<hr>

END;
  }
  //---------------------------------------------------
  // HTML �t�b�^�o��
  //---------------------------------------------------
  function footer() {
    global $init;

    print <<<END
<hr>
<div id="LinkFoot">
�Ǘ��ҁF{$init->adminName}(<a href="mailto:{$init->adminEmail}">{$init->adminEmail}</a>)<br>
�f���F(<a href="{$init->urlBbs}">{$init->urlBbs}</a>)<br>
�g�b�v�y�[�W�F(<a href="{$init->urlTopPage}">{$init->urlTopPage}</a>)
</div>
</body>
</html>

END;

    if(GZIP == true) {
      global $http;
      $http->output();
    }
  }
  //---------------------------------------------------
  // �ŏI�X�V���� �{ ���^�[���X�V�����o��
  //---------------------------------------------------
  function lastModified($hako) {
    global $init;
    $timeString = date("Y�Nm��d���@H��", $hako->islandLastTime);
    print <<<END
<h2 class="lastModified">�ŏI�X�V���� : $timeString
<span style="font-weight: normal;">
(���̃^�[���܂ŁA����
<script type="text/javascript"> <!--
 var nextTime = $hako->islandLastTime + $init->unitTime;
 remainTime(nextTime);
//-->
</script>
</span>
</h2>

END;
   }
}
//--------------------------------------------------------------------
class HtmlTop extends HTML {
  //---------------------------------------------------
  // �s�n�o�y�[�W
  //---------------------------------------------------
  function main($hako, $data) {
    global $init;

    // �ŏI�X�V���� �{ ���^�[���X�V�����o��
    $this->lastModified($hako);
    if(empty($data['defaultDevelopeMode']) || $data['defaultDevelopeMode'] == "cgi") {
      $radio = "checked"; $radio2 = "";
    } else {
      $radio = ""; $radio2 = "checked";
    }

    print "<h1>{$init->title}</h1>\n";
    if(DEBUG == true) {
      print <<<END
<form action="{$GLOBALS['THIS_FILE']}" method="post">
<input type="hidden" name="mode" value="debugTurn">
<input type="submit" value="�^�[����i�߂�">
</form>

END;
  }
    print <<<END
<h2 class='Turn'>�^�[��$hako->islandTurn</h2>
<hr>
<div id="MyIsland">
<h2>�����̓���</h2>
<form action="{$GLOBALS['THIS_FILE']}" method="post">
���Ȃ��̓��̖��O�́H<br>
<select name="ISLANDID">
$hako->islandList
</select>
<br>
�p�X���[�h���ǂ����I�I<br>
<input type="password" name="PASSWORD" value="{$data['defaultPassword']}" size="32" maxlength="32"><br>
<input type="hidden" name="mode" value="owner">
<input type="radio" name="DEVELOPEMODE" value="cgi" id="cgi" $radio><label for="cgi">�ʏ탂�[�h</label>
<input type="radio" name="DEVELOPEMODE" value="java" id="java" $radio2><label for="java">Java�X�N���v�g���[�h</label><BR>
<input type="submit" value="�J�����ɍs��">
</form>
</div>
<hr>

<div ID="IslandView">
<h2>�����̏�</h2>
<p>
���̖��O���N���b�N����ƁA<strong>�ό�</strong>���邱�Ƃ��ł��܂��B
</p>
<table border="1">
<tr>
<th {$init->bgTitleCell}>{$init->tagTH_}����{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}��{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�l��{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�ʐ�{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}����{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�H��{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�_��K��{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�H��K��{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�̌@��K��{$init->_tagTH}</th>
</tr>

END;
    for($i = 0; $i < $hako->islandNumber; $i++) {
      $island = $hako->islands[$i];
      $j = $i + 1;
      $id    = $island['id'];
      $pop   = $island['pop'] . $init->unitPop;
      $area  = $island['area'] . $init->unitArea;
      $money = Util::aboutMoney($island['money']);
      $food  = $island['food'] . $init->unitFood;
      $farm  = ($island['farm'] <= 0) ? "�ۗL����" : $island['farm'] * 10 . $init->unitPop;
      $factory  = ($island['factory'] <= 0) ? "�ۗL����" : $island['factory'] * 10 . $init->unitPop;
      $mountain = ($island['mountain'] <= 0) ? "�ۗL����" : $island['mountain'] * 10 . $init->unitPop;
      $comment  = $island['comment'];
      $comment_turn = $island['comment_turn'];
      $monster = '';
      if($island['monster'] > 0) {
        $monster = "<strong class=\"monster\">[���b{$island['monster']}��]</strong>";
      }
      
      $name = "";
      if($island['absent']  == 0) {
        $name = "{$init->tagName_}{$island['name']}��{$init->_tagName}";
      } else {
        $name = "{$init->tagName2_}{$island['name']}��({$island['absent']}){$init->_tagName2}";
      }
      if(!empty($island['owner'])) {
        $owner = $island['owner'];
      } else {
        $owner = "�R�����g";
      }
      
      $prize = $island['prize'];
      $prize = $hako->getPrizeList($prize);

      if($init->commentNew > 0 && ($comment_turn + $init->commentNew) > $hako->islandTurn) {
        $comment .= " <span class=\"new\">New</span>";
      }
      
      print "<tr>\n";
      print "<th {$init->bgNumberCell} rowspan=\"2\">{$init->tagNumber_}$j{$init->_tagNumber}</th>\n";
      print "<td {$init->bgNameCell} rowspan=\"2\"><a href=\"{$GLOBALS['THIS_FILE']}?Sight={$id}\">{$name}</a> {$monster}<br>\n{$prize}</td>\n";

      print "<td {$init->bgInfoCell}>$pop</td>\n";
      print "<td {$init->bgInfoCell}>$area</td>\n";
      print "<td {$init->bgInfoCell}>$money</td>\n";
      print "<td {$init->bgInfoCell}>$food</td>\n";
      print "<td {$init->bgInfoCell}>$farm</td>\n";
      print "<td {$init->bgInfoCell}>$factory</td>\n";
      print "<td {$init->bgInfoCell}>$mountain</td>\n";
      print "</tr>\n";
      print "<tr>\n";
      print "<td {$init->bgCommentCell} colspan=\"7\">{$init->tagTH_}{$owner}�F{$init->_tagTH}$comment</td>\n";
      print "</tr>\n";
    }
    print "</table>\n</div>\n";
    print "<hr>\n";
    $this->logPrintTop();
    $this->historyPrint();
  }
  //---------------------------------------------------
  // ���̓o�^�Ɛݒ�
  //---------------------------------------------------
  function regist(&$hako) {
    global $init;
    $this->newDiscovery($hako->islandNumber);
    $this->changeIslandInfo($hako->islandList);
    $this->changeOwnerName($hako->islandList);
    $this->setStyleSheet();
  }
  //---------------------------------------------------
  // �V��������T��
  //---------------------------------------------------
  function newDiscovery($number) {
    global $init;

    print "<div id=\"NewIsland\">\n";
    print "<h2>�V��������T��</h2>\n";
    if($number < $init->maxIsland) {
      print <<<END
<form action="{$GLOBALS['THIS_FILE']}" method="post">
�ǂ�Ȗ��O������\��H<br>
<input type="text" name="ISLANDNAME" size="32" maxlength="32">��<br>
���Ȃ��̂����O�́H(�ȗ���)<br>
<input type="text" name="OWNERNAME" size="32" maxlength="32"><br>
�p�X���[�h�́H<br>
<input type="password" name="PASSWORD" size="32" maxlength="32"><br>
�O�̂��߃p�X���[�h���������<br>
<input type="password" name="PASSWORD2" size="32" maxlength="32"><br>
<input type="hidden" name="mode" value="new">
<input type="submit" value="�T���ɍs��">
</form>
END;
    } else {
      print "���̐����ő吔�ł��E�E�E���ݓo�^�ł��܂���B\n";
    }
    print "</div>\n";
    print "<hr>\n";
  }
  //---------------------------------------------------
  // ���̖��O�ƃp�X���[�h�̕ύX
  //---------------------------------------------------
  function changeIslandInfo($islandList = "") {
    global $init;
    print <<<END
<div id="ChangeInfo">
<h2>���̖��O�ƃp�X���[�h�̕ύX</h2>
<p>
(����)���O�̕ύX�ɂ�500���~������܂��B
</p>
<form action="{$GLOBALS['THIS_FILE']}" method="post">
�ǂ̓��ł����H<br>
<select NAME="ISLANDID">
$islandList
</select>
<br>
�ǂ�Ȗ��O�ɕς��܂����H(�ύX����ꍇ�̂�)<br>
<input type="text" name="ISLANDNAME" size="32" maxlength="32">��<br>
�p�X���[�h�́H(�K�{)<br>
<input type="password" name="OLDPASS" size="32" maxlength="32"><br>
�V�����p�X���[�h�́H(�ύX���鎞�̂�)<br>
<input type="password" name="PASSWORD" size="32" maxlength="32"><br>
�O�̂��߃p�X���[�h���������(�ύX���鎞�̂�)<br>
<input type="password" name="PASSWORD2" size="32" maxlength="32"><br>
<input type="hidden" name="mode" value="change">
<input type="submit" value="�ύX����">
</form>
</div>
<hr>

END;
  }
  //---------------------------------------------------
  // �I�[�i�[���̕ύX
  //---------------------------------------------------
  function changeOwnerName($islandList = "") {
    global $init;
    print <<<END
<div id="ChangeOwnerName">
<h2>�I�[�i�[���̕ύX</h2>
<form action="{$GLOBALS['THIS_FILE']}" method="post">
�ǂ̓��ł����H<br>
<select name="ISLANDID">
{$islandList}
</select>
<br>
�V�����I�[�i�[���́H<br>
<input type="text" name="OWNERNAME" size="32" maxlength="32"><br>
�p�X���[�h�́H<br>
<input type="password" name="OLDPASS" size="32" maxlength="32"><br>
<input type="hidden" name="mode" value="ChangeOwnerName">
<input type="submit" value="�ύX����">
</form>
</div>
END;
  }
  //---------------------------------------------------
  // �X�^�C���V�[�g�̐ݒ�
  //---------------------------------------------------
  function setStyleSheet() {
    global $init;
    $styleSheet;
    for($i = 0; $i < count($init->cssList); $i++) {
      $styleSheet .= "<option value=\"{$init->cssList[$i]}\">{$init->cssList[$i]}</option>\n";
    }
    print <<<END
<div id="HakoSkin">
<h2>�X�^�C���V�[�g�̐ݒ�</h2>
<form action="{$GLOBALS['THIS_FILE']}" method="post">
<select name="SKIN">
$styleSheet
</select>
<input type="hidden" name="mode" value="skin">
<input type="submit" value="�ݒ�">
</form>
</div>
<hr>

END;
  }
  //---------------------------------------------------
  // �ŋ߂̏o����
  //---------------------------------------------------
  function logPrintTop() {
    global $init;
    print "<div id=\"RecentlyLog\">\n";
    print "<h2>�ŋ߂̏o����</h2>\n";
    for($i = 0; $i < $init->logTopTurn; $i++) {
      LogIO::logFilePrint($i, 0, 0);
    }
    print "</div>\n";
  }
  //---------------------------------------------------
  // �����̋L�^
  //---------------------------------------------------
  function historyPrint() {
    print "<div id=\"HistoryLog\">\n";
    print "<h2>�����̋L�^</h2>";
    LogIO::historyPrint();
    print "</div>\n";
  }
}
//------------------------------------------------------------------
class HtmlMap extends HTML {
  //---------------------------------------------------
  // �J�����
  //---------------------------------------------------
  function owner($hako, $data) {
    global $init;
    $id     = $data['ISLANDID'];
    $number = $hako->idToNumber[$id];
    $island = $hako->islands[$number];

    // �p�X���[�h�`�F�b�N
    if(!Util::checkPassword($island['password'], $data['PASSWORD'])){
      Error::wrongPassword();
      return;
    }
    $this->tempOwer($hako, $data, $number);
    
    if($init->useBbs) {
      print "<div id=\"localBBS\">\n";
      $this->lbbsHead($island);
      $this->lbbsInputOW($island, $data);
      $this->lbbsContents($island);
      print "</div>\n";
    }
    $this->islandRecent($island, 1);
  }

  //---------------------------------------------------
  // �ό����
  //---------------------------------------------------
  function visitor($hako, $data) {
    global $init;
    $id     = $data['ISLANDID'];
    $number = $hako->idToNumber[$id];
    $island = $hako->islands[$number];
    print <<<END
<div align="center">
{$init->tagBig_}{$init->tagName_}�u{$island['name']}���v{$init->_tagName}�ւ悤�����I�I{$init->_tagBig}<br>
{$GLOBALS['BACK_TO_TOP']}<br>
</div>

END;

    $this->islandInfo($island, $number, 0);
    $this->islandMap($hako, $island, 0);

    if($init->useBbs) {
      print "<div id=\"localBBS\">\n";
      $this->lbbsHead($island);
      $this->lbbsInput($island, $data);
      $this->lbbsContents($island);
      print "</div>\n";
    }
    $this->islandRecent($island, 0);
  }
  //---------------------------------------------------
  // ���̏��
  //---------------------------------------------------
  function islandInfo($island, $number = 0, $mode = 0) {
    global $init;
    $rank = $number + 1;
    $pop   = $island['pop'] . $init->unitPop;
    $area  = $island['area'] . $init->unitArea;
    $money = ($mode == 0) ? Util::aboutMoney($island['money']) : "{$island['money']}{$init->unitMoney}";
    $food  = $island['food'] . $init->unitFood;
    $farm  = ($island['farm'] <= 0) ? "�ۗL����" : $island['farm'] * 10 . $init->unitPop;
    $factory  = ($island['factory'] <= 0) ? "�ۗL����" : $island['factory'] * 10 . $init->unitPop;
    $mountain = ($island['mountain'] <= 0) ? "�ۗL����" : $island['mountain'] * 10 . $init->unitPop;
    $comment  = $island['comment'];

    print <<<END
<div id="islandInfo">
<table border="1">
<tr>
<th {$init->bgTitleCell}>{$init->tagTH_}����{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�l��{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�ʐ�{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}����{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�H��{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�_��K��{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�H��K��{$init->_tagTH}</th>
<th {$init->bgTitleCell}>{$init->tagTH_}�̌@��K��{$init->_tagTH}</th>
</tr>
<tr>
<th {$init->bgNumberCell}>{$init->tagNumber_}$rank{$init->_tagNumber}</th>
<td {$init->bgInfoCell}>$pop</td>
<td {$init->bgInfoCell}>$area</td>
<td {$init->bgInfoCell}>$money</td>
<td {$init->bgInfoCell}>$food</td>
<td {$init->bgInfoCell}>$farm</td>
<td {$init->bgInfoCell}>$factory</td>
<td {$init->bgInfoCell}>$mountain</td>
</tr>
<tr>
<td colspan="8" {$init->bgCommentCell}>$comment</td>
</tr>
</table>
</div>

END;
  }
  //---------------------------------------------------
  // �n�`�o��
  // $mode = 1 -- �~�T�C����n�Ȃǂ��\��
  //---------------------------------------------------
  function islandMap($hako, $island, $mode = 0) {
    global $init;
    $land      = $island['land'];
    $landValue = $island['landValue'];
    $command   = $island['command'];
    if($mode == 1) {
      for($i = 0; $i < $init->commandMax; $i++) {
        $j = $i + 1;
        $com = $command[$i];
        if($com['kind'] < 20) {
          $comStr[$com['x']][$com['y']] .=
            "[{$j}]{$init->comName[$com['kind']]} ";
        }
      }
    }

    print "<div id=\"islandMap\" align=\"center\"><table border=\"1\"><tr><td>\n";
    print "<img src=\"xbar.gif\" width=\"400\" height=\"16\" alt=\"\"><br>\n";
    for($y = 0; $y < $init->islandSize; $y++) {
      if($y % 2 == 0) { print "<img src=\"space{$y}.gif\" width=\"16\" height=\"32\" alt=\"{$y}\">"; }

      for($x = 0; $x < $init->islandSize; $x++) {
        $hako->landString($land[$x][$y], $landValue[$x][$y], $x, $y, $mode, $comStr[$x][$y]);
      }
      
      if($y % 2 == 1) { print "<img src=\"space{$y}.gif\" width=\"16\" height=\"32\" alt=\"{$y}\">"; }

      print "<br>";
    }
    print "<div id=\"NaviView\">&shy;</div>";
    print "</td></tr></table></div>\n";
  }
  //---------------------------------------------------
  // �ό��ҒʐM
  //---------------------------------------------------
  function lbbsHead($island) {
    global $init;
    print <<<END
<hr>
<h2>{$init->tagName_}{$island['name']}��{$init->_tagName}�ό��ҒʐM</h2>

END;
  }
  //---------------------------------------------------
  // �ό��ҒʐM ���͕���
  //---------------------------------------------------
  function lbbsInput($island, $data) {
    global $init;
    print <<<END
<div align="center">
<table border="1">
<tr>
<th>���O</th>
<th>���e</th>
<th>����</th>
</tr>
<tr>
<form action="{$GLOBALS['THIS_FILE']}" method="post">
<td><input type="text" size="32" maxlength="32" name="LBBSNAME" value="{$data['defaultName']}"></td>
<td><input type="text" size="80" name="LBBSMESSAGE"></td>
<td>
<input type="hidden" name="mode" value="lbbs">
<input type="hidden" name="lbbsMode" value="0">
<input type="hidden" name="ISLANDID" value="{$island['id']}">
<input type="hidden" name="DEVELOPEMODE" value="{$data['DEVELOPEMODE']}">
<input type="submit" value="�L������">
</td>
</form>
</tr>
</table>
</div>

END;
  }
  //---------------------------------------------------
  // �ό��ҒʐM ���͕��� �I�[�i�p
  //---------------------------------------------------
  function lbbsInputOW($island, $data) {
    global $init;
    print <<<END
<div align="center">
<table border="1">
<tr>
<th>���O</th>
<th colspan="2">���e</th>
</tr>
<tr>
<form action="{$GLOBALS['THIS_FILE']}" method="post">
<td><input type="text" size="32" maxlength="32" name="LBBSNAME" VALUE="{$data['defaultName']}"></TD>
<td colspan="2"><input type="text" size="80" name="LBBSMESSAGE"></td>
</tr>
<tr>
<th colspan="2">����</th>
</tr>
<tr>
<td align="right">
<input type="hidden" name="mode" value="lbbs">
<input type="hidden" name="lbbsMode" value="1">
<input type="hidden" name="PASSWORD" value="{$data['defaultPassword']}">
<input type="hidden" name="ISLANDID" value="{$island['id']}">
<input type="hidden" name="DEVELOPEMODE" value="{$data['DEVELOPEMODE']}">
<input type="submit" value="�L������">
</td>
</form>
<td align="right">
<form action="{$GLOBALS['THIS_FILE']}" method="post">
�ԍ�
<select name="NUMBER">

END;
    
    // �����ԍ�
    for($i = 0; $i < $init->lbbsMax; $i++) {
      $j = $i + 1;
      print "<option value=\"{$i}\">{$j}</option>\n";
    }
    print <<<END
</select>
<input type="hidden" name="mode" value="lbbs">
<input type="hidden" name="lbbsMode" value="2">
<input type="hidden" name="PASSWORD" value="{$data['defaultPassword']}">
<input type="hidden" name="ISLANDID" value="{$island['id']}">
<input type="hidden" name="DEVELOPEMODE" value="{$data['DEVELOPEMODE']}">
<input type="submit" value="�폜����">
</form>
</td>
</tr>
</table>
</div>

END;
  }
  //---------------------------------------------------
  // �ό��ҒʐM �������܂ꂽ���e���o��
  //---------------------------------------------------
  function lbbsContents($island) {
    global $init;
    $lbbs = $island['lbbs'];
    print <<<END
<div align="center">
<table border="1">
<tr>
<th style="width:3em;">�ԍ�</th>
<th>�L�����e</th>
</tr>

END;
    for($i = 0; $i < $init->lbbsMax; $i++) {
      $j = $i + 1;
      $line = $lbbs[$i];
      list($mode, $turn, $message) = split(">", $line);
      print "<tr><th>{$init->tagNumber_}{$j}{$init->_tagNumber}</th>";
      if($mode == 0) {
        // �ό���
        print "<td>{$init->tagLbbsSS_}{$turn} &gt; {$message}{$init->_tagLbbsSS}</td></tr>\n";
      } else {
        // ����
        print "<td>{$init->tagLbbsOW_}{$turn} &gt; {$message}{$init->_tagLbbsOW}</td></tr>\n";
      }
      
    }
    print "</table></div>\n";
  }
  //---------------------------------------------------
  // ���̋ߋ�
  //---------------------------------------------------
  function islandRecent($island, $mode = 0) {
    global $init;
    print "<hr>\n";
    print "<div id=\"RecentlyLog\">\n";
    print "<h2>{$init->tagName_}{$island['name']}��{$init->_tagName}�̋ߋ�</h2>\n";
    for($i = 0; $i < $init->logMax; $i++) {
      LogIO::logFilePrint($i, $island['id'], $mode);
    }
    print "</div>\n";
  }
  //---------------------------------------------------
  // �J�����
  //---------------------------------------------------
  function tempOwer($hako, $data, $number = 0) {
    global $init;
    $island = $hako->islands[$number];

    $width  = $init->islandSize * 32 + 50;
    $height = $init->islandSize * 32 + 100;
    $defaultTarget = ($init->targetIsland == 1) ? $island['id'] : $hako->defaultTarget;
    print <<<END
<script type="text/javascript">
<!--
var w;
var p = $defaultTarget;
function ps(x, y) {
  document.InputPlan.POINTX.options[x].selected = true;
  document.InputPlan.POINTY.options[y].selected = true;
  return true;
}

function ns(x) {
  document.InputPlan.NUMBER.options[x].selected = true;
  return true;
}

function settarget(part){
  p = part.options[part.selectedIndex].value;
}
function targetopen() {
  w = window.open("{$GLOBALS['THIS_FILE']}?target=" + p, "","width={$width},height={$height},scrollbars=1,resizable=1,toolbar=1,menubar=1,location=1,directories=0,status=1");
}


//-->
</script>
<div align="center">
{$init->tagBig_}{$init->tagName_}{$island['name']}��{$init->_tagName}�J���v��{$init->_tagBig}<br>
{$GLOBALS['BACK_TO_TOP']}<br>
</div>

END;
    $this->islandInfo($island, $number, 1);
    print <<<END
<div align="center">
<table border="1">
<tr>
<td {$init->bgInputCell}>
<div align="center">
<form action="{$GLOBALS['THIS_FILE']}" method="post" name="InputPlan">
<input type="hidden" name="mode" value="command">
<input type="hidden" name="ISLANDID" value="{$island['id']}">
<input type="hidden" name="PASSWORD" value="{$data['defaultPassword']}">
<input type="submit" value="�v�摗�M">
<hr>
<strong>�v��ԍ�</strong>
<select name="NUMBER">

END;
    // �v��ԍ�
    for($i = 0; $i < $init->commandMax; $i++) {
      $j = $i + 1;
      print "<option value=\"{$i}\">{$j}</option>";
    }
    print <<<END
</select><br>
<hr>
<strong>�J���v��</strong><br>
<select name="COMMAND">

END;
    // �R�}���h
    for($i = 0; $i < $init->commandTotal; $i++) {
      $kind = $init->comList[$i];
      $cost = $init->comCost[$kind];
      if($cost == 0) {
        $cost = '����';
      } elseif($cost < 0) {
        $cost  = - $cost;
        $cost .= $init->unitFood;
      } else {
        $cost .= $init->unitMoney;
      }
      if($kind == $data['defaultKind']) {
        $s = 'selected';
      } else {
        $s = '';
      }
      print "<option value=\"{$kind}\" {$s}>{$init->comName[$kind]}({$cost})</option>\n";
    }
    print <<<END
</select>
<hr>
<strong>���W(</strong>
<select name="POINTX">

END;
    for($i = 0; $i < $init->islandSize; $i++) {
      if($i == $data['defaultX']) {
        print "<option value=\"{$i}\" selected>{$i}</option>\n";
      } else {
        print "<option value=\"{$i}\">{$i}</option>\n";
      }
    }
    print "</select>, <select name=\"POINTY\">";
    for($i = 0; $i < $init->islandSize; $i++) {
      if($i == $data['defaultY']) {
        print "<option value=\"{$i}\" selected>{$i}</option>\n";
      } else {
        print "<option value=\"{$i}\">{$i}</option>\n";
      }
    }
    print <<<END
</select><strong>)</strong>
<hr>
<strong>����</strong>
<select name="AMOUNT">

END;
     for($i = 0; $i < 100; $i++)
       print "<option value=\"{$i}\">{$i}</option>\n";

     print <<<END
</select>
<hr>
<strong>�ڕW�̓�</strong><br>
<select name="TARGETID" onchange="settarget(this);">
$hako->targetList
</select>
<input type="button" value="�ڕW�ߑ�" onClick="javascript: targetopen();" onkeypress="javascript: targetopen();">
<hr>
<strong>����</strong><br>
<input type="radio" name="COMMANDMODE" id="insert" value="insert" checked><label for="insert">�}��</label>
<input type="radio" name="COMMANDMODE" id="write" value="write"><label for="write">�㏑��</label><BR>
<input type="radio" name="COMMANDMODE" id="delete" value="delete"><label for="delete">�폜</label>
<hr>
<input type="hidden" name="DEVELOPEMODE" value="cgi">
<input type="submit" value="�v�摗�M">
</form>
</div>
</td>
<td {$init->bgMapCell}>

END;
    $this->islandMap($hako, $island, 1);    // ���̒n�}�A���L�҃��[�h
    print <<<END
</td>
<td {$init->bgCommandCell}>
END;
    $command = $island['command'];
    for($i = 0; $i < $init->commandMax; $i++) {
      $this->tempCommand($i, $command[$i], $hako);
    }
    print <<<END
</td>
</tr>
</table>
</div>
<hr>
<div id='CommentBox'>
<h2>�R�����g�X�V</h2>
<form action="{$GLOBALS['THIS_FILE']}" method="post">
�R�����g<input type="text" name="MESSAGE" size="80" value="{$island['comment']}"><br>
<input type="hidden" name="PASSWORD" value="{$data['defaultPassword']}">
<input type="hidden" name="mode" value="comment">
<input type="hidden" name="ISLANDID" value="{$island['id']}">
<input type="hidden" name="DEVELOPEMODE" value="cgi">
<input type="submit" value="�R�����g�X�V">
</form>
</div>

END;

  }
  //---------------------------------------------------
  // ���͍ς݃R�}���h�\��
  //---------------------------------------------------
  function tempCommand($number, $command, $hako) {
    global $init;

    $kind   = $command['kind'];
    $target = $command['target'];
    $x      = $command['x'];
    $y      = $command['y'];
    $arg    = $command['arg'];

    $comName = "{$init->tagComName_}{$init->comName[$kind]}{$init->_tagComName}";
    $point   = "{$init->tagName_}({$x},{$y}){$init->_tagName}";
    $target  = $hako->idToName[$target];
    if(empty($target)) {
      $target = "���l";
    }
    $target = "{$init->tagName_}{$target}��{$init->_tagName}";
    $value = $arg * $init->comCost[$kind];
    if($value == 0) {
      $value = $init->comCost[$kind];
    }
    if($value < 0) {
      $value = -$value;
      $value = "{$value}{$init->unitFood}";
    } else {
      $value = "{$value}{$init->unitMoney}";
    }
    $value = "{$init->tagName_}{$value}{$init->_tagName}";

    $j = sprintf("%02d�F", $number + 1);

    print "<a href=\"javascript:void(0);\" onclick=\"ns({$number})\" onkeypress=\"ns({$number})\">{$init->tagNumber_}{$j}{$init->_tagNumber}";

    switch($kind) {
    case $init->comDoNothing:
    case $init->comGiveup:
    case $init->comPropaganda:
      $str = "{$comName}";
      break;
    case $init->comMissileNM:
    case $init->comMissilePP:
    case $init->comMissileST:
    case $init->comMissileLD:
      // �~�T�C���n
      $n = ($arg == 0) ? '������' : "{$arg}��";
      $str = "{$target}{$point}��{$comName}({$init->tagName_}{$n}{$init->_tagName})";
      break;
    case $init->comSendMonster:
      // ���b�h��
      $str = "{$target}��{$comName}";
      break;
    case $init->comSell:
      // �H���A�o
      $str ="{$comName}{$value}";
      break;
    case $init->comMoney:
    case $init->comFood:
      // ����
      $str = "{$target}��{$comName}{$value}";
      break;
    case $init->comDestroy:
      // �@��
      if($arg != 0) {
        $str = "{$point}��{$comName}(�\�Z{$value})";
      } else {
        $str = "{$point}��{$comName}";
      }
      break;
    case $init->comFarm:
    case $init->comFactory:
    case $init->comMountain:
      // �񐔕t��
      if($arg == 0) {
        $str = "{$point}��{$comName}";
      } else {
        $str = "{$point}��{$comName}({$arg}��)";
      }      
      break;
    default:
      // ���W�t��
      $str = "{$point}��{$comName}";
    }

    print "{$str}</a><br>";
  }
  //---------------------------------------------------
  // �V��������������
  //---------------------------------------------------
  function newIslandHead($name) {
    global $init;
    print <<<END
<div align="center">
{$init->tagBig_}���𔭌����܂����I�I{$init->_tagBig}<br>
{$init->tagBig_}{$init->tagName_}�u{$name}���v{$init->_tagName}�Ɩ������܂��B{$init->_tagBig}<br>
{$GLOBALS['BACK_TO_TOP']}<br>
</div>
END;
  }
  //---------------------------------------------------
  // �ڕW�ߑ����[�h
  //---------------------------------------------------
  function printTarget($hako, $data) {
    global $init;
    // id���瓇�ԍ����擾
    $id     = $data['ISLANDID'];
    $number = $hako->idToNumber[$id];
    // �Ȃ������̓����Ȃ��ꍇ
    if($number < 0 || $number > $hako->islandNumber) {
      Error::problem();
      return;
    }
    $island = $hako->islands[$number];

print <<<END
<script type="text/javascript">
<!--
function ps(x, y) {
  window.opener.document.InputPlan.POINTX.options[x].selected = true;
  window.opener.document.InputPlan.POINTY.options[y].selected = true;
  return true;
}
//-->
</script>

<div align="center">
{$init->tagBig_}{$init->tagName_}{$island['name']}��{$init->_tagName}{$init->_tagBig}<br>
</div>

END;

    //���̒n�}
    $this->islandMap($hako, $island, 2);

  }
}
//------------------------------------------------------------------
class HtmlJS extends HtmlMap {
  function header($data = "") {
    global $init;
    global $PRODUCT_VERSION;

    // ���k�]��
    if(GZIP == true) {
      global $http;
      $http->start();
    }
    header("X-Product-Version: {$PRODUCT_VERSION}");
    $css = (empty($data['defaultSkin'])) ? $init->cssList[0] : $data['defaultSkin'];
    print <<<END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
<base href="{$init->imgDir}/">
<meta http-equiv="Content-type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link rel="stylesheet" type="text/css" href="{$init->cssDir}/{$css}">
<title>{$init->title}</title>
<script type="text/javascript" src="{$init->baseDir}/hako.js"></script>
</head>
<body onload="init()">
<div id="LinkHeader">
<a href="http://www.bekkoame.ne.jp/~tokuoka/hakoniwa.html">���돔���X�N���v�g�z�z��</a>
<a href="http://scrlab.g-7.ne.jp/">[PHP]</a>�@
[<a href="{$GLOBALS['THIS_FILE']}?mode=conf">���̓o�^�E�ݒ�ύX</a>]
</div>
<hr>

END;
  }
  //---------------------------------------------------
  // �J�����
  //---------------------------------------------------
  function tempOwer($hako, $data, $number = 0) {
    global $init;
    $island = $hako->islands[$number];

    $width  = $init->islandSize * 32 + 50;
    $height = $init->islandSize * 32 + 100;

    // �R�}���h�Z�b�g
    $set_com = "";
    $com_max = "";
    for($i = 0; $i < $init->commandMax; $i++) {
      // �e�v�f�̎��o��
      $command  = $island['command'][$i];
      $s_kind   = $command['kind'];
      $s_target = $command['target'];
      $s_x      = $command['x'];
      $s_y      = $command['y'];
      $s_arg    = $command['arg'];

      // �R�}���h�o�^
      if($i == $init->commandMax - 1){
        $set_com .= "[$s_kind, $s_x, $s_y, $s_arg, $s_target]\n";
        $com_max .= "0";
      } else {
        $set_com .= "[$s_kind, $s_x, $s_y, $s_arg, $s_target],\n";
        $com_max .= "0,";
      }
    }

    //�R�}���h���X�g�Z�b�g
    $l_kind;
    $set_listcom = "";
    $click_com = "";
    $click_com2 = "";
    $All_listCom = 0;
    $com_count = count($init->commandDivido);
    for($m = 0; $m < $com_count; $m++) {
      list($aa,$dd,$ff) = split(",", $init->commandDivido[$m]);
      $set_listcom .= "[ ";
      for($i = 0; $i < $init->commandTotal; $i++) {
        $l_kind = $init->comList[$i];
        $l_cost = $init->comCost[$l_kind];
        if($l_cost == 0) {
          $l_cost = '����';
        } elseif($l_cost < 0) {
          $l_cost = - $l_cost; $l_cost .= $init->unitFood;
        } else {
          $l_cost .= $init->unitMoney;
        }
        if($l_kind > $dd-1 && $l_kind < $ff+1) {
          $set_listcom .= "[$l_kind, '{$init->comName[$l_kind]}', '{$l_cost}'],\n";
          if($m == 0){
            $click_com .= "<a href='javascript:void(0);' onclick='cominput(InputPlan, 6, {$l_kind})' onkeypress='cominput(InputPlan, 6, {$l_kind})' style='text-decoration:none'>{$init->comName[$l_kind]}({$l_cost})<\\/a><br>\n";
          } elseif($m == 1) {
            $click_com2 .= "<a href='javascript:void(0);' onclick='cominput(InputPlan, 6, {$l_kind})' onkeypress='cominput(InputPlan, 6, {$l_kind})' style='text-decoration:none'>{$init->comName[$l_kind]}({$l_cost})<\\/a><br>\n";
          }
          $All_listCom++;
        }
        if($l_kind < $ff+1) { next; }
      }
      $bai = strlen($set_listcom);
      $set_listcom = substr($set_listcom, 0, $bai - 2);
      $set_listcom .= " ],\n";
    }
    $bai = strlen($set_listcom);
    $set_listcom = substr($set_listcom, 0, $bai - 2);
    if(empty($data['defaultKind'])) {
      $default_Kind = 1;
    } else {
      $default_Kind = $data['defaultKind'];
    }

    // �����X�g�Z�b�g
    $set_island = "";
    for($i = 0; $i < $hako->islandNumber; $i++) {
      $l_name = $hako->islands[$i]['name'];
      $l_name = preg_replace("/'/", "\'", $l_name);
      $l_id = $hako->islands[$i]['id'];
      if($i == $hako->islandNumber - 1){
        $set_island .= "[$l_id, '$l_name']\n";
      }else{
        $set_island .= "[$l_id, '$l_name'],\n";
      }
    }
    $defaultTarget = ($init->targetIsland == 1) ? $island['id'] : $hako->defaultTarget;

    print <<<END
<div align="center">
{$init->tagBig_}{$init->tagName_}{$island['name']}��{$init->_tagName}�J���v��{$init->_tagBig}<BR>
{$GLOBALS['BACK_TO_TOP']}<br>
</div>
<script type="text/javascript">
<!--
var w;
var p = $defaultTarget;

// �i�`�u�`�X�N���v�g�J����ʔz�z��
// �����ہ[�����돔���i http://appoh.execweb.cx/hakoniwa/ �j
// Programmed by Jynichi Sakai(�����ہ[)
// �� �폜���Ȃ��ŉ������B
var str;
g = [$com_max];
k1 = [$com_max];
k2 = [$com_max];
tmpcom1 = [ [0,0,0,0,0] ];
tmpcom2 = [ [0,0,0,0,0] ];
command = [
$set_com];

comlist = [
$set_listcom
];

islname = [
$set_island];


function init() {
  for(i = 0; i < command.length ;i++) {
    for(s = 0; s < $com_count ;s++) {
      var comlist2 = comlist[s];
      for(j = 0; j < comlist2.length ; j++) {
        if(command[i][0] == comlist2[j][0]) {
          g[i] = comlist2[j][1];
        }
      }
    }
  }
  SelectList('');
  outp();
  str = plchg();
  str = '<font color="blue">�� ���M�ς� ��<\\/font><br>' + str;
  disp(str, "");

}

function cominput(theForm, x, k) {
  a = theForm.NUMBER.options[theForm.NUMBER.selectedIndex].value;
  b = theForm.COMMAND.options[theForm.COMMAND.selectedIndex].value;
  c = theForm.POINTX.options[theForm.POINTX.selectedIndex].value;
  d = theForm.POINTY.options[theForm.POINTY.selectedIndex].value;
  e = theForm.AMOUNT.options[theForm.AMOUNT.selectedIndex].value;
  f = theForm.TARGETID.options[theForm.TARGETID.selectedIndex].value;
  //  if(x == 6){ b = k; menuclose(); }
  if (x == 1 || x == 6){
    for(i = $init->commandMax - 1; i > a; i--) {
      command[i] = command[i-1];
      g[i] = g[i-1];
    }
  } else if(x == 3) {
    for(i = Math.floor(a); i < ($init->commandMax - 1); i++) {
      command[i] = command[i + 1];
      g[i] = g[i+1];
    }
    command[$init->commandMax - 1] = [41, 0, 0, 0, 0];
    g[$init->commandMax - 1] = '�����J��';
    str = plchg();
    str = '<font color="red"><strong>�� �����M ��<\\/strong><\\/font><br>' + str;
    disp(str,"white");
    outp();
    return true;
  } else if(x == 4) {
    i = Math.floor(a);
    if (i == 0){ return true; }
    i = Math.floor(a);
    tmpcom1[i] = command[i];tmpcom2[i] = command[i - 1];
    command[i] = tmpcom2[i];command[i-1] = tmpcom1[i];
    k1[i] = g[i];k2[i] = g[i - 1];
    g[i] = k2[i];g[i-1] = k1[i];
    ns(--i);
    str = plchg();
    str = '<font color="red"><strong>�� �����M ��<\\/strong><\\/font><br>' + str;
    disp(str,"white");
    outp();
    return true;
  } else if(x == 5) {
    i = Math.floor(a);
    if (i == $init->commandMax - 1){ return true; }
    tmpcom1[i] = command[i];tmpcom2[i] = command[i + 1];
    command[i] = tmpcom2[i];command[i + 1] = tmpcom1[i];
    k1[i] = g[i];k2[i] = g[i + 1];
    g[i] = k2[i];g[i + 1] = k1[i];
    ns(++i);
    str = plchg();
    str = '<font color="red"><strong>�� �����M ��<\\/strong><\\/font><br>' + str;
    disp(str,"white");
    outp();
    return true;
  }

  for(s = 0; s < $com_count; s++) {
    var comlist2 = comlist[s];
    for(i = 0; i < comlist2.length; i++){
      if(comlist2[i][0] == b){
        g[a] = comlist2[i][1];
        break;
      }
    }
  }
  command[a] = [b, c, d, e, f];
  ns(++a);
  str = plchg();
  str = '<font color="red"><b>�� �����M ��<\\/b><\\/font><br>' + str;
  disp(str, "white");
  outp();
  return true;
}
function plchg() {
  strn1 = "";
  for(i = 0; i < $init->commandMax; i++) {
    c = command[i];

    kind = '{$init->tagComName_}' + g[i] + '{$init->_tagComName}';
    x = c[1];
    y = c[2];
    tgt = c[4];
    point = '{$init->tagName_}' + "(" + x + "," + y + ")" + '{$init->_tagName}';
    for(j = 0; j < islname.length ; j++) {
      if(tgt == islname[j][0]){
        tgt = '{$init->tagName_}' + islname[j][1] + "��" + '{$init->_tagName}';
      }
    }
    if(c[0] == $init->comDoNothing || c[0] == $init->comGiveup){ // �����J��A���̕���
      strn2 = kind;
    }else if(c[0] == $init->comMissileNM || // �~�T�C���֘A
             c[0] == $init->comMissilePP ||
             c[0] == $init->comMissileST ||
             c[0] == $init->comMissileLD){
      if(c[3] == 0) {
        arg = "�i�������j";
      } else {
        arg = "�i" + c[3] + "���j";
      }
      strn2 = tgt + point + "��" + kind + arg;
    } else if(c[0] == $init->comSendMonster) { // ���b�h��
      strn2 = tgt + "��" + kind;
    } else if(c[0] == $init->comSell) { // �H���A�o
      if(c[3] == 0){ c[3] = 1; }
      arg = c[3] * 100;
      arg = "�i" + arg + "{$init->unitFood}�j";
      strn2 = kind + arg;
    } else if(c[0] == $init->comPropaganda) { // �U�v����
      strn2 = kind;
    } else if(c[0] == $init->comMoney) { // ��������
      if(c[3] == 0){ c[3] = 1; }
      arg = c[3] * {$init->comCost[$init->comMoney]};
      arg = "�i" + arg + "{$init->unitMoney}�j";
      strn2 = tgt + "��" + kind + arg;
    } else if(c[0] == $init->comFood) { // �H������
      if(c[3] == 0){ c[3] = 1; }
      arg = c[3] * 100;
      arg = "�i" + arg + "{$init->unitFood}�j";
      strn2 = tgt + "��" + kind + arg;
    } else if(c[0] == $init->comDestroy) { // �@��
      if(c[3] == 0){
        strn2 = point + "��" + kind;
      } else {
        arg = c[3] * {$init->comCost[$init->comDestroy]};
        arg = "�i�\\�Z" + arg + "{$init->unitMoney}�j";
        strn2 = point + "��" + kind + arg;
      }
    } else if(c[0] == $init->comFarm || // �_��A�H��A�̌@�ꐮ��
              c[0] == $init->comFactory ||
              c[0] == $init->comMountain) {
      if(c[3] != 0){
        arg = "�i" + c[3] + "��j";
        strn2 = point + "��" + kind + arg;
      }else{
        strn2 = point + "��" + kind;
      }
    }else{
      strn2 = point + "��" + kind;
    }
    tmpnum = '';
    if(i < 9){ tmpnum = '0'; }
    strn1 +=
      '<a style="text-decoration:none;color:000000" HREF="javascript:void(0);" onclick="ns(' + i + ')" onkeypress="ns(' + i + ')"><nobr>' +
        tmpnum + (i + 1) + ':' +
          strn2 + '<\\/nobr><\\/a><br>\\n';
  }
  return strn1;
}

function disp(str,bgclr) {
  if(str==null)  str = "";

  if(document.getElementById){
    document.getElementById("LINKMSG1").innerHTML = str;
    if(bgclr != "")
      document.getElementById("plan").bgColor = bgclr;
  } else if(document.all){
    el = document.all("LINKMSG1");
    el.innerHTML = str;
    if(bgclr != "")
      document.all.plan.bgColor = bgclr;
  } else if(document.layers) {
    lay = document.layers["PARENT_LINKMSG"].document.layers["LINKMSG1"];
    lay.document.open();
    lay.document.write("<font style='font-size:11pt'>"+str+"<\\/font>");
    lay.document.close();
    if(bgclr != "")
      document.layers["PARENT_LINKMSG"].bgColor = bgclr;
  }
}

function outp() {
  comary = "";

  for(k = 0; k < command.length; k++){
    comary = comary + command[k][0]
      + " " + command[k][1]
        + " " + command[k][2]
          + " " + command[k][3]
            + " " + command[k][4]
              + " " ;
  }
  document.InputPlan.COMARY.value = comary;
}

function ps(x, y) {
  document.InputPlan.POINTX.options[x].selected = true;
  document.InputPlan.POINTY.options[y].selected = true;
  return true;
}


function ns(x) {
  if (x == $init->commandMax){ return true; }
  document.InputPlan.NUMBER.options[x].selected = true;
  return true;
}

function set_com(x, y, land) {
  com_str = land + " ";
  for(i = 0; i < $init->commandMax; i++) {
    c = command[i];
    x2 = c[1];
    y2 = c[2];
    if(x == x2 && y == y2 && c[0] < 30){
      com_str += "[" + (i + 1) +"]" ;
      kind = g[i];
      if(c[0] == $init->comDestroy){
        if(c[3] == 0){
          com_str += kind;
        } else {
          arg = c[3] * 200;
          arg = "�i�\\�Z" + arg + "{$init->unitMoney}�j";
          com_str += kind + arg;
        }
      } else if(c[0] == $init->comFarm ||
                c[0] == $init->comFactory ||
                c[0] == $init->comMountain) {
        if(c[3] != 0){
          arg = "�i" + c[3] + "��j";
          com_str += kind + arg;
        } else {
          com_str += kind;
        }
      } else {
        com_str += kind;
      }
      com_str += " ";
    }
  }
  document.InputPlan.COMSTATUS.value= com_str;
}


function SelectList(theForm) {
  var u, selected_ok;
  if(!theForm) { s = '' }
  else { s = theForm.menu.options[theForm.menu.selectedIndex].value; }
  if(s == ''){
    u = 0; selected_ok = 0;
    document.InputPlan.COMMAND.options.length = $All_listCom;
    for (i=0; i<comlist.length; i++) {
      var command = comlist[i];
      for (a=0; a<command.length; a++) {
        comName = command[a][1] + "(" + command[a][2] + ")";
        document.InputPlan.COMMAND.options[u].value = command[a][0];
        document.InputPlan.COMMAND.options[u].text = comName;
        if(command[a][0] == $default_Kind){
          document.InputPlan.COMMAND.options[u].selected = true;
          selected_ok = 1;
        }
        u++;
      }
    }
    if(selected_ok == 0)
      document.InputPlan.COMMAND.selectedIndex = 0;
  } else {
    var command = comlist[s];
    document.InputPlan.COMMAND.options.length = command.length;
    for (i=0; i<command.length; i++) {
      comName = command[i][1] + "(" + command[i][2] + ")";
      document.InputPlan.COMMAND.options[i].value = command[i][0];
      document.InputPlan.COMMAND.options[i].text = comName;
      if(command[i][0] == $default_Kind){
        document.InputPlan.COMMAND.options[i].selected = true;
        selected_ok = 1;
      }
    }
    if(selected_ok == 0)
      document.InputPlan.COMMAND.selectedIndex = 0;
  }
}


function settarget(part){
  p = part.options[part.selectedIndex].value;
}
function targetopen() {
  w = window.open("{$GLOBALS['THIS_FILE']}?target=" + p, "","width={$width},height={$height},scrollbars=1,resizable=1,toolbar=1,menubar=1,location=1,directories=0,status=1");
}

    //-->
</script>
END;

    $this->islandInfo($island, $number, 1);

    print <<<END
<div align="center">
<table border="1">
<tr valign="top">
<td $init->bgInputCell>
<form action="{$GLOBALS['THIS_FILE']}" method="post" name="InputPlan">
<input type="hidden" name="mode" value="command">
<input type="hidden" name="COMARY" value="comary">
<input type="hidden" name="DEVELOPEMODE" value="java">
<div align="center">
<br>
<b>�R�}���h����</b><br>
<b>
<a href="javascript:void(0);" onclick="cominput(InputPlan,1)" onkeypress="cominput(InputPlan,1)">�}��</a>
�@<a href="javascript:void(0);" onclick="cominput(InputPlan,2)" onkeypress="cominput(InputPlan,2)">�㏑��</a>
�@<a href="javascript:void(0);" onclick="cominput(InputPlan,3)" onkeypress="cominput(InputPlan,3)">�폜</a>
</b>
<hr>
<b>�v��ԍ�</b>
<select name="NUMBER">
END;

    // �v��ԍ�
    for($i = 0; $i < $init->commandMax; $i++) {
      $j = $i + 1;
      print "<option value=\"$i\">$j</option>\n";
    }

    if ($HmenuOpen == 'on') {
      $open = "CHECKED";
    }else{
      $open = "";
    }

    print <<<END
</select>
<hr>
<b>�J���v��</b>
<br>
<select name="menu" onchange="SelectList(InputPlan)">
<option value="">�S���</option>

END;

    for($i = 0; $i < $com_count; $i++) {
      list($aa, $tmp) = split(",", $init->commandDivido[$i], 2);
      print "<option value=\"$i\">{$aa}</option>\n";
    }
    print <<<END
</select><br>
<select name="COMMAND">
<option>�@�@�@�@�@�@�@�@�@�@</option>
<option>�@�@�@�@�@�@�@�@�@�@</option>
<option>�@�@�@�@�@�@�@�@�@�@</option>
<option>�@�@�@�@�@�@�@�@�@�@</option>
<option>�@�@�@�@�@�@�@�@�@�@</option>
<option>�@�@�@�@�@�@�@�@�@�@</option>
<option>�@�@�@�@�@�@�@�@�@�@</option>
<option>�@�@�@�@�@�@�@�@�@�@</option>
<option>�@�@�@�@�@�@�@�@�@�@</option>
<option>�@�@�@�@�@�@�@�@�@�@</option>
</select>
<hr>
<b>���W(</b>
<select name="POINTX">

END;

    for($i = 0; $i < $init->islandSize; $i++) {
      if($i == $data['defaultX']) {
        print "<option value=\"$i\" selected>$i</option>\n";
      } else {
        print "<option value=\"$i\">$i</option>\n";
      }
    }

    print "</select>, <select name=\"POINTY\">\n";

    for($i = 0; $i < $init->islandSize; $i++) {
      if($i == $data['defaultY']) {
        print "<option value=\"$i\" selected>$i</option>\n";
      } else {
        print "<option value=\"$i\">$i</option>\n";
      }
    }

    print <<<END
</select><b> )</b>
<hr>
<b>����</b><select name="AMOUNT">

END;

    // ����
    for($i = 0; $i < 100; $i++) {
      print "<option value=\"$i\">$i</option>\n";
    }

    print <<<END
</select>
<hr>
<b>�ڕW�̓�</b><br>
<select name="TARGETID" onchange="settarget(this);">
$hako->targetList
</select>
<input type="button" value="�ڕW�ߑ�" onClick="javascript: targetopen();" onkeypress="javascript: targetopen();">
<hr>
<b>�R�}���h�ړ�</b>�F
<a href="javascript:void(0);" onclick="cominput(InputPlan,4)" onkeypress="cominput(InputPlan,4)" style="text-decoration:none"> �� </a>�E�E
<a href="javascript:void(0);" onclick="cominput(InputPlan,5)" onkeypress="cominput(InputPlan,5)" style="text-decoration:none"> �� </a>
<hr>
<input type="hidden" name="ISLANDID" value="{$island['id']}">
<input type="hidden" name="PASSWORD" value="{$data['defaultPassword']}">
<input type="submit" value="�v�摗�M">
<br>�Ō��<font color="red">�v�摗�M�{�^��</font>��<br>�����̂�Y��Ȃ��悤�ɁB
</div>
</form>
</td>
<td $init->bgMapCell><div align="center">

END;

    $this->islandMap($hako, $island, 1);    // ���̒n�}�A���L�҃��[�h

    $comment = $hako->islands[$number]['comment'];

    print <<<END
</div>
</td>
<td $init->bgCommandCell id="plan">
<ilayer name="PARENT_LINKMSG" width="100%" height="100%">
<layer name="LINKMSG1" width="200"></layer>
<span id="LINKMSG1"></span>
</ilayer>
<br>
</td>
</tr>
</table>
</div>
<hr>
<div id='CommentBox'>
<h2>�R�����g�X�V</h2>
<form action="{$GLOBALS['THIS_FILE']}" method="post">
�R�����g<input type="text" name="MESSAGE" size="80" value="{$island['comment']}"><br>
<input type="hidden" name="PASSWORD" value="{$data['defaultPassword']}">
<input type="hidden" name="mode" value="comment">
<input type="hidden" name="DEVELOPEMODE" value="java">
<input type="hidden" name="ISLANDID" value="{$island['id']}">
<input type="submit" value="�R�����g�X�V">
</FORM>
</DIV>
END;

  }
}



class HtmlSetted extends HTML {
  function setSkin() {
    global $init;
    print "{$init->tagBig_}�X�^�C���V�[�g��ݒ肵�܂����B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
  }
  function comment() {
    global $init;
    print "{$init->tagBig_}�R�����g���X�V���܂���{$init->_tagBig}<hr>";
  }
  function change() {
    global $init;
    print "{$init->tagBig_}�ύX�������܂���{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
  }
  function lbbsDelete() {
    global $init;
    print "{$init->tagBig_}�L�����e���폜���܂���{$init->_tagBig}<hr>";
  }
  function lbbsAdd() {
    global $init;
    print "{$init->tagBig_}�L�����s���܂���{$init->_tagBig}<hr>";
  }
  // �R�}���h�폜
  function commandDelete() {
    global $init;
    print "{$init->tagBig_}�R�}���h���폜���܂���{$init->_tagBig}<hr>\n";
  }

  // �R�}���h�o�^
  function commandAdd() {
    global $init;
    print "{$init->tagBig_}�R�}���h��o�^���܂���{$init->_tagBig}<hr>\n";
  }
}
class Error {
  function wrongPassword() {
    global $init;
    print "{$init->tagBig_}�p�X���[�h���Ⴂ�܂��B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  // hakojima.dat���Ȃ�
  function noDataFile() {
    global $init;
    print "{$init->tagBig_}�f�[�^�t�@�C�����J���܂���B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function newIslandFull() {
    global $init;
    print "{$init->tagBig_}�\���󂠂�܂���A������t�œo�^�ł��܂���I�I{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function newIslandNoName() {
    global $init;
    print "{$init->tagBig_}���ɂ��閼�O���K�v�ł��B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function newIslandBadName() {
    global $init;
    print "{$init->tagBig_},?()<>\$�Ƃ������Ă���A�u���l���v�Ƃ��������ςȖ��O�͂�߂܂��傤��`�B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function newIslandAlready() {
    global $init;
    print "{$init->tagBig_}���̓��Ȃ炷�łɔ�������Ă��܂��B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function newIslandNoPassword() {
    global $init;
    print "{$init->tagBig_}�p�X���[�h���K�v�ł��B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function changeNoMoney() {
    global $init;
    print "{$init->tagBig_}�����s���̂��ߕύX�ł��܂���{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function changeNothing() {
    global $init;
    print "{$init->tagBig_}���O�A�p�X���[�h�Ƃ��ɋ󗓂ł�{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function problem() {
    global $init;
    print "{$init->tagBig_}��蔭���A�Ƃ肠�����߂��Ă��������B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function lbbsNoMessage() {
    global $init;
    print "{$init->tagBig_}���O�܂��͓��e�̗����󗓂ł��B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
  function lockFail() {
    global $init;
    print "{$init->tagBig_}�����A�N�Z�X�G���[�ł��B<BR>�u���E�U�́u�߂�v�{�^���������A<BR>���΂炭�҂��Ă���ēx�������������B{$init->_tagBig}{$GLOBALS['BACK_TO_TOP']}\n";
    HTML::footer();
    exit;
  }
}
?>
