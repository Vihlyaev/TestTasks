<?php
/*
Plugin Name: Additional User Fields
Description: WordPress плагин, расширяющий профиль пользователя дополнительными метаполями.
Version: 1.0
*/


// Шифрование.

class MyEnc {
	public $pubkey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDN8yURBQaO2RF9p66nWuJeNIoS
8hUvgEu8jkOOtsbWpfNzmUxMshS/RdnS6pQ9szQVU2Y2RU4nloN1cmpN3KecHSbE
gWVVhjPImuBfsTLLrk89hshBLtvz3zyYZ1sRvaFkkRz9jI+sYgKMb2tX6Ulb197F
qU2XQiaKidddcufT4wIDAQAB
-----END PUBLIC KEY-----';
	public $privkey = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDN8yURBQaO2RF9p66nWuJeNIoS8hUvgEu8jkOOtsbWpfNzmUxM
shS/RdnS6pQ9szQVU2Y2RU4nloN1cmpN3KecHSbEgWVVhjPImuBfsTLLrk89hshB
Ltvz3zyYZ1sRvaFkkRz9jI+sYgKMb2tX6Ulb197FqU2XQiaKidddcufT4wIDAQAB
AoGBAMmBnIPRkgAUnUt/1KCEiTY5W9t7p4Tpl7Du1WT+qwB8MP6rQH9OQoR/mMgI
o3DXNR+kkT6KgxSy7P7uKtryCb8xZC3tHgMjsCtH3IsbxjK/Z5QrI0x/MtzJX/wo
nXw1pNlCusxd0aIPz292Nvx4hIBmSeJl7hmn9akqwwjgNQgRAkEA5w2lP+SN2JQ8
u5rtjmQBtzvVa678NmwPT6iXZCR+VZFmIDrJ7CGLigC4r0v/FNlvcBlxcT9b7Lq3
nwm6+CakeQJBAOQvojDoPXkYTnEs8e7ez+IjhHTdRCVjpf39qKTcJXQLEFMDRQz8
TbQrhqLWQbhhFZVDIu4xCd1Qk2TCk8+MTDsCQQCswF8VbmU/0VW3Txbep5dA0NO5
N5QLfK/V90956G8suxGId2wRyOLzw6ZSKpgFlqzjO6K98YlhbhlsJ3JLp1jBAkAv
ZcmN2R+zwJ3CmnBiirupoHEKCU/3aDx5hq/6FpRdB1NLuvkj2mCVlRjxSAn8r4XZ
LEncKil/ZBZJrrUfPmgzAkBCvRtyIudUyCgr9S2GkUFx0wLZ78/tk7zb+n3ekdZf
9NShu2Qf9YMWwj2aMyXck0a/rBURSAv2BoJfXnsZyUQi
-----END RSA PRIVATE KEY-----';

	public
	function encrypt( $data ) {
		if ( openssl_public_encrypt( $data, $encrypted, $this->pubkey ) )
			$data = base64_encode( $encrypted );
		else
			throw new Exception( 'Unable to encrypt data. Perhaps it is bigger than the key size?' );
		return $data;
	}
	public
	function decrypt( $data ) {
		if ( openssl_private_decrypt( base64_decode( $data ), $decrypted, $this->privkey ) )
			$data = $decrypted;
		else
			$data = '';
		return $data;
	}
}
$obj = new MyEnc();



// Добавление метаполей.

function show_additional_metafields( $user ) {
	global $obj;
	?>
	<h3>Дополнительные метаполя</h3>
	<table class="form-table">
		<tr>
			<th><label for="address">Адрес</label>
			</th>
			<td><input type="text" name="address" id="address" value="<?php echo $obj->decrypt(esc_attr(get_the_author_meta('address',$user->ID)));?>" class="regular-text"/><br/>
			</td>
		</tr>
		<tr>
			<th><label for="phone">Телефон</label>
			</th>
			<td><input type="text" name="phone" id="phone" value="<?php echo $obj->decrypt(esc_attr(get_the_author_meta('phone',$user->ID)));?>" class="regular-text"/><br/>
			</td>
		</tr>
		<th><label for="gender">Пол</label>
		</th>
		<td>
			<?php $gender = $obj->decrypt(get_the_author_meta('gender',$user->ID )); ?>
			<ul>
				<li><label><input value="мужской" name="gender"<?php if ($gender == 'мужской') { ?> checked="checked"<?php } ?> type="radio" /> мужской</label>
				</li>
				<li><label><input value="женский"  name="gender"<?php if ($gender == 'женский') { ?> checked="checked"<?php } ?> type="radio" /> женский</label>
				</li>
			</ul>
		</td>
		</tr>
		<th><label for="family_status">Семейный статус</label>
		</th>
		<td>
			<?php $family_status = $obj->decrypt(get_the_author_meta('family_status',$user->ID )); ?>
			<ul>
				<li><label><input value="Не замужем/не женат" name="family_status"<?php if ($family_status == 'Не замужем/не женат') { ?> checked="checked"<?php } ?> type="radio" /> Не замужем/не женат</label>
				</li>
				<li><label><input value="Замужем/женат"  name="family_status"<?php if ($family_status == 'Замужем/женат') { ?> checked="checked"<?php } ?> type="radio" /> Замужем/женат</label>
				</li>
				<li><label><input value="Разведен/разведена"  name="family_status"<?php if ($family_status == 'Разведен/разведена') { ?> checked="checked"<?php } ?> type="radio" /> Разведен/разведена</label>
				</li>
				<li><label><input value="Гражданский брак"  name="family_status"<?php if ($family_status == 'Гражданский брак') { ?> checked="checked"<?php } ?> type="radio" /> Гражданский брак</label>
				</li>
			</ul>
		</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'show_additional_metafields' );
add_action( 'edit_user_profile', 'show_additional_metafields' );



// Сохранение метаполей.

function save_additional_metafields( $user_id ) {
	global $obj;
	update_usermeta( $user_id, 'address', $obj->encrypt( $_POST[ 'address' ] ) );
	update_usermeta( $user_id, 'phone', $obj->encrypt( $_POST[ 'phone' ] ) );
	update_usermeta( $user_id, 'gender', $obj->encrypt( $_POST[ 'gender' ] ) );
	update_usermeta( $user_id, 'family_status', $obj->encrypt( $_POST[ 'family_status' ] ) );
}
add_action( 'personal_options_update', 'save_additional_metafields' );
add_action( 'edit_user_profile_update', 'save_additional_metafields' );



// Вывод списка пользователей.

function user_list() {
	$users = get_users();
	echo '<table><tr><th>Фото</th><th>Имя</th><th>Фамилия</th><th>Email</th><th></th></tr>';
	foreach ( $users as $user ) {
		?>
		<tr>
			<td>
				<?php echo get_avatar( $user->ID, 50 );?>
			</td>
			<td>
				<?php echo $user->first_name;?>
			</td>
			<td>
				<?php echo $user->last_name;?>
			</td>
			<td>
				<?php echo $user->user_email;?>
			</td>
			<td><a href="http://www.b6a9a3x5.idua.org/view-profile/?id=<?php echo $user->ID;?>">Профиль</a>
			</td>
		</tr>
		<?php
	}
	echo '</table>';
}
add_shortcode( 'list', 'user_list' );

?>