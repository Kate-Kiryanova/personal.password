<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
	Bitrix\Main\Loader,
	Bitrix\Main\Application,
	Bitrix\Main\Localization\Loc;

class FlxMDEditPersonalPassword extends CBitrixComponent
{

	private $arRequest = [];

	private $bCheckFields = false;
	private $nUserID;
	private $objUser;
	private $arUser;
	private $bCheckPassword = false;
	private $bUpdatePassword = false;
	private $arResponse = [];

	public function executeComponent()
	{
		Loc::loadMessages(__FILE__);

		$this->arResult["PARAMS_HASH"] = md5(serialize($this->arParams).$this->GetTemplateName());

		$this->arRequest = Application::getInstance()->getContext()->getRequest();

		if (
			$this->arRequest->isAjaxRequest() &&
			$this->arRequest->getPost('FLXMD_AJAX') === 'Y' &&
			$this->arRequest->getPost('PARAMS_HASH') === $this->arResult["PARAMS_HASH"]
		) {
			$this->getPersonalData();
			$this->checkFields();

			if ($this->bCheckFields)
				$this->checkPassword();

			if ($this->bCheckPassword)
				$this->updatePassword();

			if ($this->bUpdatePassword)
				$this->sendEmail();

			$this->sendResponseAjax();

		} else {
			$this->IncludeComponentTemplate();
		}
	}

	public function getPersonalData()
	{
		global $USER;

		$this->nUserID = $USER->GetID();
		$this->objUser = $USER;

		$this->arUser = CUser::GetByID($this->nUserID)->Fetch();
	}

	public function checkFields()
	{
		if (
			$this->arRequest->getPost('PARAMS_HASH') === $this->arResult["PARAMS_HASH"] &&
			empty($this->arRequest->getPost('CHECK_EMPTY')) &&
			!empty($this->arRequest->getPost('personal-password')) &&
			!empty($this->arRequest->getPost('personal-new-password')) &&
			!empty($this->arRequest->getPost('personal-new-password-repeat')) &&
			htmlspecialchars($this->arRequest->getPost('personal-new-password')) == htmlspecialchars($this->arRequest->getPost('personal-new-password-repeat')) &&
			check_bitrix_sessid()
		) {
			$this->bCheckFields = true;
		} else {
			$this->arResponse = ['STATUS' => 'ERROR', 'MESSAGE' => Loc::getMessage("FLXMD_PERSONAL_PASSWORD_FIELDS_ERROR")];
		}
	}

	public function checkPassword()
	{
		$salt = substr($this->arUser['PASSWORD'], 0, 8);
		if ($this->arUser['PASSWORD'] === $salt.md5($salt.htmlspecialchars($this->arRequest->getPost('personal-password')))) {
			$this->bCheckPassword = true;
		} else {
			$this->arResponse = ['STATUS' => 'ERROR', 'MESSAGE' => Loc::getMessage('FLXMD_PERSONAL_PASSWORD_CHECK_PASSWORD_ERROR')];
		}
	}

	public function updatePassword()
	{
		$obUser = new CUser;
		$res = $obUser->Update(
			$this->arUser['ID'],
			array(
				"PASSWORD" => htmlspecialchars($this->arRequest->getPost('personal-new-password'))
			)
		);
		if(!$res) {
			$this->arResponse = ['STATUS' => 'ERROR', 'MESSAGE' => $obUser->LAST_ERROR];
		} else {
			$this->bUpdatePassword = true;
		}
	}

	public function sendEmail()
	{
		$arFields = array(
			'EMAIL' => $this->arUser['EMAIL'],
			'PASSWORD' => htmlspecialchars($this->arRequest->getPost('personal-new-password'))
		);

		if (CEvent::Send("USER_PASS_CHANGED", SITE_ID, $arFields)) {
			$this->arResponse = ['STATUS' => 'SUCCESS'];
		} else {
			$this->arResponse = ['STATUS' => 'ERROR', 'MESSAGE' => Loc::getMessage("FLXMD_PERSONAL_PASSWORD_MAIL_ERROR")];
		}
	}

	public function sendResponseAjax() {

		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		echo json_encode($this->arResponse);

		die();

	}

}
