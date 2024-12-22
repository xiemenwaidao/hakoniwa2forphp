<?php
/*******************************************************************

  ���돔���Q for PHP

  - �����ݒ�p�t�@�C�� -
  
  $Id: config.php,v 1.7 2003/09/29 11:54:19 Watson Exp $

*******************************************************************/
define("GZIP", false);	// true: GZIP ���k�]�����g�p  false: �g�p���Ȃ�
define("DEBUG", false);	// true: �f�o�b�O false: �ʏ�
define("LOCK_RETRY_COUNT", 10);		// �t�@�C�����b�N�����̃��g���C��
define("LOCK_RETRY_INTERVAL", 1000);// �ă��b�N�������{�܂ł̎���(�~���b)�B�Œ�ł�500���炢���w��

class Init {
  //----------------------------------------
  // �e��ݒ�l
  //----------------------------------------
  // �v���O������u���f�B���N�g��
  var $baseDir		= "http://127.0.0.1/hako_php";

  // �摜��u���f�B���N�g��
  var $imgDir		= "http://127.0.0.1/hako_image";

  // CSS�t�@�C����u���f�B���N�g��
  var $cssDir		= "http://127.0.0.1/hako_php/css";

  // CSS���X�g
  var $cssList		= array('SkyBlue.css', 'Autumn.css');
  
  //�p�X���[�h�̈Í��� true: �Í����Afalse: �Í������Ȃ�
  var $cryptOn		= true; 
  // �}�X�^�[�p�X���[�h
  var $masterPassword	= "hogehoge";
  var $specialPassword	= "hogehogehoge";
  
  // �f�[�^�f�B���N�g���̖��O
  var $dirName		= "data";

  // �Q�[���^�C�g��
  var $title		= "���돔���Q";

  var $adminName	= "xxx";
  var $adminEmail	= "xxx@xxx.ne.jp";
  var $urlBbs		= "http://xxx.xxx.xxx/";
  var $urlTopPage	= "http://xxx.xxx.xxx/";

  // �f�B���N�g���쐬���̃p�[�~�V����
  var $dirMode		= 0755;
  
  // 1�^�[�������b��
  var $unitTime		= 21600; // 6����

  // ���̍ő吔
  var $maxIsland	= 20;

  // �����\�����[�h
  var $moneyMode	= true; // true: 100�̈ʂŎl�̌ܓ�, false: ���̂܂�
  // �g�b�v�y�[�W�ɕ\�����郍�O�̃^�[����
  var $logTopTurn	= 1;
  // ���O�t�@�C���ێ��^�[����
  var $logMax		= 8;

  // �o�b�N�A�b�v�����^�[�������Ɏ�邩
  var $backupTurn	= 12;
  // �o�b�N�A�b�v�����񕪎c����
  var $backupTimes	= 4;

  // �������O�ێ��s��
  var $historyMax	= 10;

  // �����R�}���h�������̓^�[����
  var $giveupTurn	= 28;

  // �R�}���h���͌��E��
  var $commandMax	= 20;

  // ���[�J���f���s�����g�p���邩�ǂ���(false:�g�p���Ȃ��Atrue:�g�p����)
  var $useBbs		= true;
  // ���[�J���f���s��
  var $lbbsMax		= 5;

  // ���̑傫��
  var $islandSize	= 12;

  // ��������
  var $initialMoney	= 100;
  // �����H��
  var $initialFood	= 100;

  // �����ő�l
  var $maxMoney		= 9999;
  // �H���ő�l
  var $maxFood		= 9999;

  // �l���̒P��
  var $unitPop		= "00�l";
  // �H���̒P��
  var $unitFood		= "00�g��";
  // �L���̒P��
  var $unitArea		= "00����";
  // �؂̐��̒P��
  var $unitTree		= "00�{";
  // �����̒P��
  var $unitMoney	= "���~";

  // �؂̒P�ʓ�����̔��l
  var $treeValue	= 5;

  // ���O�ύX�̃R�X�g
  var $costChangeName	= 500;

  // �l��1�P�ʂ�����̐H�����
  var $eatenFood	= 0.2;

  // ���c�̎���
  var $oilMoney		= 1000;
  // ���c�̌͊��m��
  var $oilRatio		= 40;


  // �~�T�C����n
  // �o���l�̍ő�l
  var $maxExpPoint	= 200; // �������A�ő�ł�255�܂�

  // ���x���̍ő�l
  var $maxBaseLevel	= 5; // �~�T�C����n
  var $maxSBaseLevel	= 3; // �C���n

  // �o���l�������Ń��x���A�b�v��
  var $baseLevelUp	= array(20, 60, 120, 200); // �~�T�C����n
  var $sBaseLevelUp	= array(50, 200);          // �C���n

  // ���b�ɓ��܂ꂽ����������Ȃ�1�A���Ȃ��Ȃ�0
  var $dBaseAuto = 1;

  // �ڕW�̓� ���L�̓����I�����ꂽ��ԂŃ��X�g�𐶐� 1�A���ʂ�TOP�̓��Ȃ�0
  // �~�T�C���̌�˂������ꍇ�ȂǂɎg�p����Ɨǂ���������Ȃ�
  var $targetIsland = 1;
  
  var $disEarthquake = 5;  // �n�k
  var $disTsunami    = 15; // �Ôg
  var $disTyphoon    = 20; // �䕗
  var $disMeteo      = 15; // 覐�
  var $disHugeMeteo  = 5;  // ����覐�
  var $disEruption   = 10; // ����
  var $disFire       = 10; // �΍�
  var $disMaizo      = 10; // ������

  // �n�Ւ���
  var $disFallBorder = 90; // ���S���E�̍L��(Hex��)
  var $disFalldown   = 30; // ���̍L���𒴂����ꍇ�̊m��

  // ���b
  var $disMonsBorder1 = 1000; // �l���1(���b���x��1)
  var $disMonsBorder2 = 2500; // �l���2(���b���x��2)
  var $disMonsBorder3 = 4000; // �l���3(���b���x��3)
  var $disMonster     = 3;    // �P�ʖʐς�����̏o����(0.01%�P��)

  var $monsterLevel1  = 2; // �T���W���܂�    
  var $monsterLevel2  = 5; // ���̂�S�[�X�g�܂�
  var $monsterLevel3  = 7; // �L���O���̂�܂�(�S��)

  var $monsterNumber	= 8; // ���b�̎��
  // ���b�̖��O
  var $monsterName	= array (
    '���J���̂�',
    '���̂�',
    '�T���W��',
    '���b�h���̂�',
    '�_�[�N���̂�',
    '���̂�S�[�X�g',
    '�N�W��',
    '�L���O���̂�'
    );
  // ���b�̉摜
  var $monsterImage	= array (
    'monster7.gif',
    'monster0.gif',
    'monster5.gif',
    'monster1.gif',
    'monster2.gif',
    'monster8.gif',
    'monster6.gif',
    'monster3.gif',
    );
  // �摜�t�@�C������2(�d����)
  var $monsterImage2	= array ('', '', 'monster4.gif', '', '', '', 'monster4.gif', '');

  // �Œ�̗́A�̗͂̕��A����\�́A�o���l�A���̂̒l�i
  var $monsterBHP	= array( 2,   1,   1,    3,   2,   1,    4,    5,   3,   2,   3,    5,    6);
  var $monsterDHP	= array( 0,   2,   2,    2,   2,   0,    2,    2,   1,   2,   2,    2,    3);
  var $monsterSpecial	= array( 0,   0,   3,    6,   1,   2,    4,    7,   5,   1,   2,    9,   10);
  var $monsterExp	= array( 5,   5,   7,   12,  15,  10,   20,   30,  20,  15,  20,   50,  100);
  var $monsterValue	= array( 0, 400, 500, 1000, 800, 300, 1500, 2000, 500, 600, 800, 3000, 3500);

  // �^�[���t�����^�[�����ɏo����
  var $turnPrizeUnit	= 100;

  // �܂̖��O
  var $prizeName	= array (
    '�^�[���t',
    '�ɉh��',
    '���ɉh��',
    '���ɔɉh��',
    '���a��',
    '�����a��',
    '���ɕ��a��',
    '�Г��',
    '���Г��',
    '���ɍГ��',
    );

  // �L�O��
  var $monumentNumber	= 1;
  var $monumentName	= array (
    '���m���X',
    );
  // �摜�t�@�C��
  var $monumentImage = array (
    'monument0.gif',
    );

  /********************
      �O���֌W
   ********************/
  // �傫������
  var $tagBig_ = '<span class="big">';
  var $_tagBig = '</span>';
  // ���̖��O�Ȃ�
  var $tagName_ = '<span class="islName">';
  var $_tagName = '</span>';
  // �����Ȃ������̖��O
  var $tagName2_ = '<span class="islName2">';
  var $_tagName2 = '</span>';
  // ���ʂ̔ԍ��Ȃ�
  var $tagNumber_ = '<span class="number">';
  var $_tagNumber = '</span>';
  // ���ʕ\�ɂ����錩����
  var $tagTH_ = '<span class="head">';
  var $_tagTH = '</span>';
  // �J���v��̖��O
  var $tagComName_ = '<span class="command">';
  var $_tagComName = '</span>';
  // �ЊQ
  var $tagDisaster_ = '<span class="disaster">';
  var $_tagDisaster = '</span>';
  // ���[�J���f���A�ό��҂̏���������
  var $tagLbbsSS_ = '<span class="lbbsSS">';
  var $_tagLbbsSS = '</span>';
  // ���[�J���f���A����̏���������
  var $tagLbbsOW_ = '<span class="lbbsOW">';
  var $_tagLbbsOW = '</span>';
  // ���ʕ\�A�Z���̑���
  var $bgTitleCell   = 'class="TitleCell"';   // ���ʕ\���o��
  var $bgNumberCell  = 'class="NumberCell"';  // ���ʕ\����
  var $bgNameCell    = 'class="NameCell"';    // ���ʕ\���̖��O
  var $bgInfoCell    = 'class="InfoCell"';    // ���ʕ\���̏��
  var $bgCommentCell = 'class="CommentCell"'; // ���ʕ\�R�����g��
  var $bgInputCell   = 'class="InputCell"';   // �J���v��t�H�[��
  var $bgMapCell     = 'class="MapCell"';     // �J���v��n�}
  var $bgCommandCell = 'class="CommandCell"'; // �J���v����͍ς݌v��

  /********************
      �n�`�ԍ�
   ********************/

  var $landSea		=  0; // �C
  var $landWaste	=  1; // �r�n
  var $landPlains	=  2; // ���n
  var $landTown		=  3; // ���n
  var $landForest	=  4; // �X
  var $landFarm		=  5; // �_��
  var $landFactory	=  6; // �H��
  var $landBase		=  7; // �~�T�C����n
  var $landDefence	=  8; // �h�q�{��
  var $landMountain	=  9; // �R
  var $landMonster	= 10; // ���b
  var $landSbase	= 11; // �C���n
  var $landOil		= 12; // �C����c
  var $landMonument	= 13; // �L�O��
  var $landHaribote	= 14; // �n���{�e
  /********************
       �R�}���h
   ********************/
  // �R�}���h����
  // ���̃R�}���h���������́A�������͌n�̃R�}���h�͐ݒ肵�Ȃ��ŉ������B
  var $commandDivido = 
	array(
	'�J��,0,10',  // �v��ԍ�00�`10
	'����,11,30', // �v��ԍ�11�`30
	'�U��,31,40', // �v��ԍ�31�`40
	'�^�c,41,60'  // �v��ԍ�41�`60
	);
  // ���ӁF�X�y�[�X�͓���Ȃ��悤��
  // ����	'�J��,0,10',  # �v��ԍ�00�`10
  // �~��	'�J��, 0  ,10  ',  # �v��ԍ�00�`10

  var $commandTotal	= 28; // �R�}���h�̎��
  // ����
  var $comList;
  // ���n�n
  var $comPrepare	= 01; // ���n
  var $comPrepare2	= 02; // �n�Ȃ炵
  var $comReclaim	= 03; // ���ߗ���
  var $comDestroy	= 04; // �@��
  var $comSellTree	= 05; // ����

  // ���n
  var $comPlant		= 11; // �A��
  var $comFarm		= 12; // �_�ꐮ��
  var $comFactory	= 13; // �H�ꌚ��
  var $comMountain	= 14; // �̌@�ꐮ��
  var $comBase		= 15; // �~�T�C����n����
  var $comDbase		= 16; // �h�q�{�݌���
  var $comSbase		= 17; // �C���n����
  var $comMonument	= 18; // �L�O�茚��
  var $comHaribote	= 19; // �n���{�e�ݒu

  // ���ˌn
  var $comMissileNM	= 31; // �~�T�C������
  var $comMissilePP	= 32; // PP�~�T�C������
  var $comMissileST	= 33; // ST�~�T�C������
  var $comMissileLD	= 34; // ���n�j��e����
  var $comSendMonster	= 35; // ���b�h��

  // �^�c�n
  var $comDoNothing	= 41; // �����J��
  var $comSell		= 42; // �H���A�o
  var $comMoney		= 43; // ��������
  var $comFood		= 44; // �H������
  var $comPropaganda	= 45; // �U�v����
  var $comGiveup	= 46; // ���̕���

  // �������͌n
  var $comAutoPrepare	= 61; // �t�����n
  var $comAutoPrepare2	= 62; // �t���n�Ȃ炵
  var $comAutoDelete	= 63; // �S�R�}���h����

  var $comName;
  var $comCost;

  // ���̍��W��
  var $pointNumber;

  // ����2�w�b�N�X�̍��W
  var $ax = array(0, 1, 1, 1, 0,-1, 0, 1, 2, 2, 2, 1, 0,-1,-1,-2,-1,-1, 0);
  var $ay = array(0,-1, 0, 1, 1, 0,-1,-2,-1, 0, 1, 2, 2, 2, 1, 0,-1,-2,-2);

  // �R�����g�ȂǂɁA�\\��̂悤��\������ɒǉ������
  var $stripslashes;

  function setVariable() {
    $this->pointNumber = $this->islandSize * $this->islandSize;

    $this->comList	= array(
      $this->comPrepare,
      $this->comSell,
      $this->comPrepare2,
      $this->comReclaim,
      $this->comDestroy,
      $this->comSellTree,
      $this->comPlant,
      $this->comFarm,
      $this->comFactory,
      $this->comMountain,
      $this->comBase,
      $this->comDbase,
      $this->comSbase,
      $this->comMonument,
      $this->comHaribote,
      $this->comMissileNM,
      $this->comMissilePP,
      $this->comMissileST,
      $this->comMissileLD,
      $this->comSendMonster,
      $this->comDoNothing,
      $this->comMoney,
      $this->comFood,
      $this->comPropaganda,
      $this->comGiveup,
      $this->comAutoPrepare,
      $this->comAutoPrepare2,
      $this->comAutoDelete,
      );
    // �v��̖��O�ƒl�i
    $this->comName[$this->comPrepare]      = '���n';
    $this->comCost[$this->comPrepare]      = 5;
    $this->comName[$this->comPrepare2]     = '�n�Ȃ炵';
    $this->comCost[$this->comPrepare2]     = 100;
    $this->comName[$this->comReclaim]      = '���ߗ���';
    $this->comCost[$this->comReclaim]      = 150;
    $this->comName[$this->comDestroy]      = '�@��';
    $this->comCost[$this->comDestroy]      = 200;
    $this->comName[$this->comSellTree]     = '����';
    $this->comCost[$this->comSellTree]     = 0;
    $this->comName[$this->comPlant]        = '�A��';
    $this->comCost[$this->comPlant]        = 50;
    $this->comName[$this->comFarm]         = '�_�ꐮ��';
    $this->comCost[$this->comFarm]         = 20;
    $this->comName[$this->comFactory]      = '�H�ꌚ��';
    $this->comCost[$this->comFactory]      = 100;
    $this->comName[$this->comMountain]     = '�̌@�ꐮ��';
    $this->comCost[$this->comMountain]     = 300;
    $this->comName[$this->comBase]         = '�~�T�C����n����';
    $this->comCost[$this->comBase]         = 300;
    $this->comName[$this->comDbase]        = '�h�q�{�݌���';
    $this->comCost[$this->comDbase]        = 800;
    $this->comName[$this->comSbase]        = '�C���n����';
    $this->comCost[$this->comSbase]        = 8000;
    $this->comName[$this->comMonument]     = '�L�O�茚��';
    $this->comCost[$this->comMonument]     = 9999;
    $this->comName[$this->comHaribote]     = '�n���{�e�ݒu';
    $this->comCost[$this->comHaribote]     = 1;
    $this->comName[$this->comMissileNM]    = '�~�T�C������';
    $this->comCost[$this->comMissileNM]    = 20;
    $this->comName[$this->comMissilePP]    = 'PP�~�T�C������';
    $this->comCost[$this->comMissilePP]    = 50;
    $this->comName[$this->comMissileST]    = 'ST�~�T�C������';
    $this->comCost[$this->comMissileST]    = 50;
    $this->comName[$this->comMissileLD]    = '���n�j��e����';
    $this->comCost[$this->comMissileLD]    = 100;
    $this->comName[$this->comSendMonster]  = '���b�h��';
    $this->comCost[$this->comSendMonster]  = 3000;
    $this->comName[$this->comDoNothing]    = '�����J��';
    $this->comCost[$this->comDoNothing]    = 0;
    $this->comName[$this->comSell]         = '�H���A�o';
    $this->comCost[$this->comSell]         = -100;
    $this->comName[$this->comMoney]        = '��������';
    $this->comCost[$this->comMoney]        = 100;
    $this->comName[$this->comFood]         = '�H������';
    $this->comCost[$this->comFood]         = -100;
    $this->comName[$this->comPropaganda]   = '�U�v����';
    $this->comCost[$this->comPropaganda]   = 1000;
    $this->comName[$this->comGiveup]       = '���̕���';
    $this->comCost[$this->comGiveup]       = 0;
    $this->comName[$this->comAutoPrepare]  = '���n��������';
    $this->comCost[$this->comAutoPrepare]  = 0;
    $this->comName[$this->comAutoPrepare2] = '�n�Ȃ炵��������';
    $this->comCost[$this->comAutoPrepare2] = 0;
    $this->comName[$this->comAutoDelete]   = '�S�v��𔒎��P��';
    $this->comCost[$this->comAutoDelete]   = 0;

  }
  function Init() {
    $this->setVariable();
    mt_srand(time());
    // ���{���Ԃɂ��킹��
    // �C�O�̃T�[�o�ɐݒu����ꍇ�͎��̍s�ɂ���//���͂����B
    // putenv("TZ=JST-9");

    // �\\��̂悤��\������ɒǉ������
    $this->stripslashes	= get_magic_quotes_gpc();
  }
}
?>
