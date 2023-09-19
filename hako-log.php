<?php
/*******************************************************************

  ���돔���Q for PHP

  
  $Id: hako-log.php,v 1.2 2004/08/10 22:00:03 Watson Exp $

*******************************************************************/

class Log extends LogIO {

  function discover($name) {
    global $init;
    $this->history("{$init->tagName_}{$name}��{$init->_tagName}�����������B");
  }
  function changeName($name1, $name2) {
    global $init;
    $this->history("{$init->tagName_}{$name1}��{$init->_tagName}�A���̂�{$init->tagName_}{$name2}��{$init->_tagName}�ɕύX����B");
  }
  // ���
  function prize($id, $name, $pName) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��<strong>$pName</strong>����܂��܂����B",$id);
    $this->history("{$init->tagName_}{$name}��{$init->_tagName}�A<strong>$pName</strong>�����");
  }
  // ����
  function dead($id, $name) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}����l�����Ȃ��Ȃ�A<strong>���l��</strong>�ɂȂ�܂����B", $id);
    $this->history("{$init->tagName_}{$name}��{$init->_tagName}�A�l�����Ȃ��Ȃ�<strong>���l��</strong>�ƂȂ�B");
  }
  function doNothing($id, $name, $comName) {
    //global $init;
    //$this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂����B",$id);
  }
  // ��������Ȃ�
  function noMoney($id, $name, $comName) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�ŗ\�肳��Ă���{$init->tagComName_}{$comName}{$init->_tagComName}�́A�����s���̂��ߒ��~����܂����B",$id);
  }
  // �H������Ȃ�
  function noFood($id, $name, $comName) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�ŗ\�肳��Ă���{$init->tagComName_}{$comName}{$init->_tagComName}�́A���~�H���s���̂��ߒ��~����܂����B",$id);
  }
  // �Ώےn�`�̎�ނɂ�鎸�s
  function landFail($id, $name, $comName, $kind, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�ŗ\�肳��Ă���{$init->tagComName_}{$comName}{$init->_tagComName}�́A�\��n��{$init->tagName_}{$point}{$init->_tagName}��<strong>{$kind}</strong>���������ߒ��~����܂����B",$id);
  }
  // ����
  function landSuc($id, $name, $comName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂����B",$id);
  }
  // ������
  function maizo($id, $name, $comName, $value) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�ł�{$init->tagComName_}{$comName}{$init->_tagComName}���ɁA<strong>{$value}{$init->unitMoney}���̖�����</strong>����������܂����B",$id);
  }
  function noLandAround($id, $name, $comName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�ŗ\�肳��Ă���{$init->tagComName_}{$comName}{$init->_tagComName}�́A�\��n��{$init->tagName_}{$point}{$init->_tagName}�̎��ӂɗ��n���Ȃ��������ߒ��~����܂����B",$id);
  }
  // ���c����
  function oilFound($id, $name, $point, $comName, $str) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$str}</strong>�̗\�Z��������{$init->tagComName_}{$comName}{$init->_tagComName}���s���A<strong>���c���@�蓖�Ă��܂���</strong>�B",$id);
  }
  // ���c�����Ȃ炸
  function oilFail($id, $name, $point, $comName, $str) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$str}</strong>�̗\�Z��������{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A���c�͌�����܂���ł����B",$id);
  }
  // �h�q�{�݁A�����Z�b�g
  function bombSet($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��<strong>�������u���Z�b�g</strong>����܂����B",$id);
  }
  // �h�q�{�݁A�����쓮
  function bombFire($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>�A{$init->tagDisaster_}�������u�쓮�I�I{$init->_tagDisaster}",$id);
  }
  // �A��or�~�T�C����n
  function PBSuc($id, $name, $comName, $point) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$point}{$init->_tagName}��{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂����B",$id);
    $this->out("������Ȃ����A{$init->tagName_}{$name}��{$init->_tagName}��<strong>�X</strong>���������悤�ł��B",$id);
  }
  // �n���{�e
  function hariSuc($id, $name, $comName, $comName2, $point) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$point}{$init->_tagName}��{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂����B",$id);
    $this->landSuc($id, $name, $comName2, $point);
  }
  // �L�O��A����
  function monFly($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��<strong>�����ƂƂ��ɔ�ї����܂���</strong>�B",$id);
  }
  // �~�T�C�����Ƃ��Ƃ���(or ���b�h�����悤�Ƃ���)���^�[�Q�b�g�����Ȃ�
  function msNoTarget($id, $name, $comName) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�ŗ\�肳��Ă���{$init->tagComName_}{$comName}{$init->_tagComName}�́A�ڕW�̓��ɐl����������Ȃ����ߒ��~����܂����B",$id);
  }
  // �X�e���X�~�T�C�����������͈͊O
  function msOutS($id, $tId, $name, $tName, $comName, $point) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��$point{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A<strong>�̈�O�̊C</strong>�ɗ������͗l�ł��B",$id, $tId);
    $this->late("<strong>���҂�</strong>��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�֌�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A<strong>�̈�O�̊C</strong>�ɗ������͗l�ł��B",$tId);
  }
  // �~�T�C�����������͈͊O
  function msOut($id, $tId, $name, $tName, $comName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A<strong>�̈�O�̊C</strong>�ɗ������͗l�ł��B",$id, $tId);
  }
  // �X�e���X�~�T�C�����������h�q�{�݂ŃL���b�`
  function msCaughtS($id, $tId, $name, $tName, $comName, $point, $tPoint) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A{$init->tagName_}{$tPoint}{$init->_tagName}�n�_���ɂė͏�ɑ������A<strong>�󒆔���</strong>���܂����B",$id, $tId);
    $this->late("<strong>���҂�</strong>��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�֌�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A{$init->tagName_}{$tPoint}{$init->_tagName}�n�_���ɂė͏�ɑ������A<strong>�󒆔���</strong>���܂����B",$tId);
  }
  // �~�T�C�����������h�q�{�݂ŃL���b�`
  function msCaught($id, $tId, $name, $tName, $comName, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A{$init->tagName_}{$tPoint}{$init->_tagName}�n�_���ɂė͏�ɑ������A<strong>�󒆔���</strong>���܂����B",$id, $tId);
  }
  // �X�e���X�~�T�C�������������ʂȂ�
  function msNoDamageS($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɗ������̂Ŕ�Q������܂���ł����B",$id, $tId);
    $this->late("<strong>���҂�</strong>��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɗ������̂Ŕ�Q������܂���ł����B",$tId);
  }  
  // �~�T�C�������������ʂȂ�
  function msNoDamage($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɗ������̂Ŕ�Q������܂���ł����B",$id, $tId);
  }
  // ���n�j��e�A�R�ɖ���
  function msLDMountain($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɖ����B<strong>{$tLname}</strong>�͏�����сA�r�n�Ɖ����܂����B",$id, $tId);
  }
  // ���n�j��e�A�C���n�ɖ���
  function msLDSbase($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}�ɒ����㔚���A���n�_�ɂ�����<strong>{$tLname}</strong>�͐Ռ`���Ȃ�������т܂����B",$id, $tId);
  }
  // ���n�j��e�A���b�ɖ���
  function msLDMonster($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}�ɒ��e�������B���n��<strong>���b{$tLname}</strong>����Ƃ����v���܂����B",$id, $tId);
  }
  // ���n�j��e�A�󐣂ɖ���
  function msLDSea1($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɒ��e�B�C�ꂪ�������܂����B",$id, $tId);
  }
  // ���n�j��e�A���̑��̒n�`�ɖ���
  function msLDLand($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɒ��e�B���n�͐��v���܂����B",$id, $tId);
  }
  // �X�e���X�~�T�C���A�r�n�ɒ��e
  function msWasteS($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɗ����܂����B",$id, $tId);
    $this->late("<strong>���҂�</strong>��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɗ����܂����B",$tId);
  }
  // �ʏ�~�T�C���A�r�n�ɒ��e
  function msWaste($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂������A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɗ����܂����B",$id, $tId);
  }
  // �X�e���X�~�T�C���A���b�ɖ����A�d�����ɂĖ���
  function msMonNoDamageS($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>���b{$tLname}</strong>�ɖ����A�������d����Ԃ��������ߌ��ʂ�����܂���ł����B",$id, $tId);
    $this->out("<strong>���҂�</strong>��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>���b{$tLname}</strong>�ɖ����A�������d����Ԃ��������ߌ��ʂ�����܂���ł����B",$tId);
  }
  // �ʏ�~�T�C���A���b�ɖ����A�d�����ɂĖ���
  function msMonNoDamage($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>���b{$tLname}</strong>�ɖ����A�������d����Ԃ��������ߌ��ʂ�����܂���ł����B",$id, $tId);
  }
  // �X�e���X�~�T�C���A���b�ɖ����A�E��
  function msMonKillS($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>���b{$tLname}</strong>�ɖ����B<strong>���b{$tLname}</strong>�͗͐s���A�|��܂����B",$id, $tId);
    $this->late("<strong>���҂�</strong>��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>���b{$tLname}</strong>�ɖ����B<strong>���b{$tLname}</strong>�͗͐s���A�|��܂����B", $tId);
  }
  // �ʏ�~�T�C���A���b�ɖ����A�E��
  function msMonKill($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>���b{$tLname}</strong>�ɖ����B<strong>���b{$tLname}</strong>�͗͐s���A�|��܂����B",$id, $tId);
  }
  // ���b�̎���
  function msMonMoney($tId, $mName, $value) {
    global $init;
    $this->out("<strong>���b{$mName}</strong>�̎c�[�ɂ́A<strong>{$value}{$init->unitMoney}</strong>�̒l���t���܂����B",$tId);
  }
  // �X�e���X�~�T�C���A���b�ɖ����A�_���[�W
  function msMonsterS($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>���b{$tLname}</strong>�ɖ����B<strong>���b{$tLname}</strong>�͋ꂵ�����ə��K���܂����B",$id, $tId);
    $this->late("<strong>���҂�</strong>��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>���b{$tLname}</strong>�ɖ����B<strong>���b{$tLname}</strong>�͋ꂵ�����ə��K���܂����B",$tId);
  }
  // �ʏ�~�T�C���A���b�ɖ����A�_���[�W
  function msMonster($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>���b{$tLname}</strong>�ɖ����B<strong>���b{$tLname}</strong>�͋ꂵ�����ə��K���܂����B",$id, $tId);
  }
  // �X�e���X�~�T�C���ʏ�n�`�ɖ���
  function msNormalS($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->secret("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɖ����A��т���ł��܂����B",$id, $tId);
    $this->late("<strong>���҂�</strong>��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɖ����A��т���ł��܂����B",$tId);
  }
  // �ʏ�~�T�C���ʏ�n�`�ɖ���
  function msNormal($id, $tId, $name, $tName, $comName, $tLname, $point, $tPoint) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$point}{$init->_tagName}�n�_�Ɍ�����{$init->tagComName_}{$comName}{$init->_tagComName}���s���A{$init->tagName_}{$tPoint}{$init->_tagName}��<strong>{$tLname}</strong>�ɖ����A��т���ł��܂����B",$id, $tId);
  }
  // �~�T�C�����Ƃ��Ƃ�������n���Ȃ�
  function msNoBase($id, $name, $comName) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�ŗ\�肳��Ă���{$init->tagComName_}{$comName}{$init->_tagComName}�́A<strong>�~�T�C���ݔ���ۗL���Ă��Ȃ�</strong>���߂Ɏ��s�ł��܂���ł����B",$id);
  }
  // �~�T�C�������
  function msBoatPeople($id, $name, $achive) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�ɂǂ�����Ƃ��Ȃ�<strong>{$achive}{$init->unitPop}���̓</strong>���Y�����܂����B{$init->tagName_}{$name}��{$init->_tagName}�͉����󂯓��ꂽ�悤�ł��B",$id);
  }
  // ���b�h��
  function monsSend($id, $tId, $name, $tName) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��<strong>�l�����b</strong>�������B{$init->tagName_}{$tName}��{$init->_tagName}�֑��肱�݂܂����B",$id, $tId);
  }
  // �A�o
  function sell($id, $name, $comName, $value) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��<strong>{$value}{$init->unitFood}</strong>��{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂����B",$id);
  }
  // ����
  function aid($id, $tId, $name, $tName, $comName, $str) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagName_}{$tName}��{$init->_tagName}��<strong>{$str}</strong>��{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂����B",$id, $tId);
  }
  // �U�v����
  function propaganda($id, $name, $comName) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagComName_}{$comName}{$init->_tagComName}���s���܂����B",$id);
  }
  // ����
  function giveup($id, $name) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�͕�������A<strong>���l��</strong>�ɂȂ�܂����B",$id);
    $this->history("{$init->tagName_}{$name}��{$init->_tagName}�A��������<strong>���l��</strong>�ƂȂ�B");
  }
  // ���c����̎���
  function oilMoney($id, $name, $lName, $point, $str) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>����A<strong>{$str}</strong>�̎��v���オ��܂����B",$id);
  }
  // ���c�͊�
  function oilEnd($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>�͌͊������悤�ł��B",$id);
  }
  // ���b�A�h�q�{�݂𓥂�
  function monsMoveDefence($id, $name, $lName, $point, $mName) {
    global $init;
    $this->out("<strong>���b{$mName}</strong>��{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>�֓��B�A<strong>{$lName}�̎������u���쓮�I�I</strong>",$id);
  }
  // ���b����
  function monsMove($id, $name, $lName, $point, $mName) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��<strong>���b{$mName}</strong>�ɓ��ݍr�炳��܂����B",$id);
  }
  // �΍�
  function fire($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��{$init->tagDisaster_}�΍�{$init->_tagDisaster}�ɂ���ł��܂����B",$id);
  }
  // �L���Q�A�C�̌���
  function wideDamageSea2($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>�͐Ռ`���Ȃ��Ȃ�܂����B",$id);
  }
  // �L���Q�A���b���v
  function wideDamageMonsterSea($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}�̗��n��<strong>���b{$lName}</strong>����Ƃ����v���܂����B",$id);
  }
  // �L���Q�A���v
  function wideDamageSea($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��<strong>���v</strong>���܂����B",$id);
  }
  // �L���Q�A���b
  function wideDamageMonster($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>���b{$lName}</strong>�͏�����т܂����B",$id);
  }
  // �L���Q�A�r�n
  function wideDamageWaste($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>�͈�u�ɂ���<strong>�r�n</strong>�Ɖ����܂����B",$id);
  }
  // �n�k����
  function earthquake($id, $name) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�ő�K�͂�{$init->tagDisaster_}�n�k{$init->_tagDisaster}�������I�I",$id);
  }
  // �n�k��Q
  function eQDamage($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��{$init->tagDisaster_}�n�k{$init->_tagDisaster}�ɂ���ł��܂����B",$id);
  }
  // �Q��
  function starve($id, $name) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagDisaster_}�H�����s��{$init->_tagDisaster}���Ă��܂��I�I",$id);
  }
  // �H���s����Q
  function svDamage($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��<strong>�H�������߂ďZ�����E��</strong>�B<strong>{$lName}</strong>�͉�ł��܂����B",$id);
  }
  // �Ôg����
  function tsunami($id, $name) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}�t�߂�{$init->tagDisaster_}�Ôg{$init->_tagDisaster}�����I�I",$id);
  }
  // �Ôg��Q
  function tsunamiDamage($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��{$init->tagDisaster_}�Ôg{$init->_tagDisaster}�ɂ����󂵂܂����B",$id);
  }
  // ���b����
  function monsCome($id, $name, $mName, $point, $lName) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��<strong>���b{$mName}</strong>�o���I�I{$init->tagName_}{$point}{$init->_tagName}��<strong>{$lName}</strong>�����ݍr�炳��܂����B",$id);
  }
  // �n�Ւ�������
  function falldown($id, $name) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagDisaster_}�n�Ւ���{$init->_tagDisaster}���������܂����I�I",$id);
  }
  // �n�Ւ�����Q
  function falldownLand($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>�͊C�̒��֒��݂܂����B",$id);
  }
  // �䕗����
  function typhoon($id, $name) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$init->_tagName}��{$init->tagDisaster_}�䕗{$init->_tagDisaster}�㗤�I�I",$id);
  }

  // �䕗��Q
  function typhoonDamage($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��{$init->tagDisaster_}�䕗{$init->_tagDisaster}�Ŕ�΂���܂����B",$id);
  }
  // 覐΁A���̑�
  function hugeMeteo($id, $name, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}�n�_��{$init->tagDisaster_}����覐�{$init->_tagDisaster}�������I�I",$id);
  }
  // �L�O��A����
  function monDamage($id, $name, $point) {
    global $init;
    $this->out("<strong>�����ƂĂ��Ȃ�����</strong>��{$init->tagName_}{$name}��{$point}{$init->_tagName}�n�_�ɗ������܂����I�I",$id);
  }
  // 覐΁A�C
  function meteoSea($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��{$init->tagDisaster_}覐�{$init->_tagDisaster}���������܂����B",$id);
  }
  // 覐΁A�R
  function meteoMountain($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��{$init->tagDisaster_}覐�{$init->_tagDisaster}�������A<strong>{$lName}</strong>�͏�����т܂����B",$id);
  }
  // 覐΁A�C���n
  function meteoSbase($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}��<strong>{$lName}</strong>��{$init->tagDisaster_}覐�{$init->_tagDisaster}�������A<strong>{$lName}</strong>�͕��󂵂܂����B",$id);
  }
  // 覐΁A���b
  function meteoMonster($id, $name, $lName, $point) {
    global $init;
    $this->out("<strong>���b{$lName}</strong>������{$init->tagName_}{$name}��{$point}{$init->_tagName}�n�_��{$init->tagDisaster_}覐�{$init->_tagDisaster}�������A���n��<strong>���b{$lName}</strong>����Ƃ����v���܂����B",$id);
  }
  // 覐΁A��
  function meteoSea1($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}�n�_��{$init->tagDisaster_}覐�{$init->_tagDisaster}�������A�C�ꂪ�������܂����B",$id);
  }
  // 覐΁A���̑�
  function meteoNormal($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}�n�_��<strong>{$lName}</strong>��{$init->tagDisaster_}覐�{$init->_tagDisaster}�������A��т����v���܂����B",$id);
  }
  // ����
  function eruption($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}�n�_��{$init->tagDisaster_}�ΎR������{$init->_tagDisaster}�A<strong>�R</strong>���o���܂����B",$id);
  }
  // ���΁A��
  function eruptionSea1($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}�n�_��<strong>{$lName}</strong>�́A{$init->tagDisaster_}����{$init->_tagDisaster}�̉e���ŗ��n�ɂȂ�܂����B",$id);
  }
  // ���΁A�Cor�C��
  function eruptionSea($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}�n�_��<strong>{$lName}</strong>�́A{$init->tagDisaster_}����{$init->_tagDisaster}�̉e���ŊC�ꂪ���N�A�󐣂ɂȂ�܂����B",$id);
  }
  // ���΁A���̑�
  function eruptionNormal($id, $name, $lName, $point) {
    global $init;
    $this->out("{$init->tagName_}{$name}��{$point}{$init->_tagName}�n�_��<strong>{$lName}</strong>�́A{$init->tagDisaster_}����{$init->_tagDisaster}�̉e���ŉ�ł��܂����B",$id);
  }
}

?>