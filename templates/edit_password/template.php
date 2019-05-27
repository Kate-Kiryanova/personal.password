<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<div class="personal__block anim-fadein">

	<h2 class="personal__title">
		<?= Loc::getMessage('PERSONAL_PASSWORD_TITLE'); ?>
	</h2>

	<form class="form personal__form js-validate js-validate-disabled" id="personal-pass-form" action="<?=POST_FORM_ACTION_URI;?>" method="post" autocomplete="off" data-modal-success="personal-pass-success">

		<?=bitrix_sessid_post();?>

		<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>" />
		<input type="hidden" name="FLXMD_AJAX" value="Y" />
		<input type="hidden" name="CHECK_EMPTY" value="" />

		<div class="form__message js-error-container"></div>

		<div class="form__row">

			<div class="form__item" id="personal-pass-prev-item">
				<label class="form__label" for="personal-pass-prev">
					<?= Loc::getMessage('PERSONAL_PASSWORD_OLD_PASSWORD'); ?>
				</label>
				<input
					class="input"
					id="personal-pass-prev"
					type="password"
					name="personal-password"
					required
					placeholder="<?= Loc::getMessage('PERSONAL_PASSWORD_OLD_PASSWORD_PLACEHOLDER'); ?>"
					data-required-message="<?= Loc::getMessage('PERSONAL_PASSWORD_REQUIRED_FIELD'); ?>"
					data-error-target="#personal-pass-prev"
				/>
			</div>

			<div class="form__item" id="personal-pass-new-item">
				<label class="form__label" for="personal-pass-new">
					<?= Loc::getMessage('PERSONAL_PASSWORD_NEW_PASSWORD'); ?>
				</label>
				<input
					class="input"
					id="personal-pass-new"
					type="password"
					name="personal-new-password"
					required
					placeholder="<?= Loc::getMessage('PERSONAL_PASSWORD_NEW_PASSWORD_PLACEHOLDER'); ?>"
					data-required-message="<?= Loc::getMessage('PERSONAL_PASSWORD_REQUIRED_FIELD'); ?>"
					data-error-target="#personal-pass-new-item"
				/>
			</div>

		</div>

		<div class="form__row">

			<div class="form__item" id="personal-pass-repeat-item">
				<label class="form__label" for="personal-pass-repeat">
					<?= Loc::getMessage('PERSONAL_PASSWORD_NEW_PASSWORD_REPEAT'); ?>
				</label>
				<input
					class="input"
					id="personal-pass-repeat"
					type="password"
					name="personal-new-password-repeat"
					required
					placeholder="<?= Loc::getMessage('PERSONAL_PASSWORD_NEW_PASSWORD_REPEAT'); ?>"
					data-required-message="<?= Loc::getMessage('PERSONAL_PASSWORD_REQUIRED_FIELD'); ?>"
					data-target-match="#personal-pass-new"
					data-mismatch-message="<?= Loc::getMessage('PERSONAL_PASSWORD_ERROR'); ?>"
					data-error-target="#personal-pass-repeat-item"
				/>
			</div>

			<div class="form__item">
				<button class="btn btn--big form__btn personal__submit" type="submit">
					<?= Loc::getMessage('PERSONAL_PASSWORD_SAVE'); ?>
				</button>
			</div>

		</div>

	</form>
</div>
