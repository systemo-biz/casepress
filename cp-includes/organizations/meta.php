<?php


/*
Plugin Name: Organization meta data
*/

class OrganizationMeta_CP_Singleton {

private static $_instance = null;

private function __construct() {
    add_action( 'add_meta_boxes', array( &$this, 'add' ) );
    add_action( 'save_post', array( &$this, 'save' ), 1, 2 );
    add_filter( 'the_content', array( &$this, 'view' ));
}

  //init metabox
  function add() {
      add_meta_box('organization_contacts_cp', __('Organization contacts', 'casepress'), array(&$this, 'mb_callback'), 'organizations', 'normal', 'high');
  }

  //print HTML add_contacts
  function mb_callback($post){
      wp_nonce_field( basename( __FILE__ ), 'organization_contact_meta_nonce' );

      $email = get_post_meta($post->ID, 'email',true);
      $tel = get_post_meta($post->ID, 'tel',true);
      //Если в телефоне есть буквы, то их убираем
      $tel = filter_var($tel, FILTER_SANITIZE_NUMBER_INT);

      $contacts_others = get_post_meta($post->ID, 'contacts_others',true);

      ?>
          <p>
              <label for="phone">Телефон (основной):</label><br/>
              <small>Номер телефона, который используется чаще всего. Пример 71234567890</small><br/>
              <input type="number" name="tel" id="phone" class="field_cp" value="<?php echo $tel ?>" size="50">
          </p>
          <p>
              <label for="email">Email (основной):</label><br/>
              <input type="text" name="email" id="email" class="field_cp" value="<?php echo $email ?>" size="50">
          </p>
          <p>
              <label for="contacts_others">Прочие контактные данные:</label><br/>
              <textarea rows="3" cols="70" name="contacts_others" id="contacts_others" class="field_cp"><?php echo $contacts_others ?></textarea>
          </p>
      <?php
  }

  //save meta data
  function save($post_id) {

      // check wpnonce
      if ( !isset( $_POST['organization_contact_meta_nonce'] ) || !wp_verify_nonce( $_POST['organization_contact_meta_nonce'], basename( __FILE__ ) ) ) return $post_id;

      // if autosave then cancel
      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;

      //user can?
      if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;

      //go
      $post = get_post($post_id);
      if ($post->post_type == 'organizations') { // укажите собственный
          update_post_meta($post_id, 'email', esc_attr($_POST['email']));
          update_post_meta($post_id, 'tel', esc_attr($_POST['tel']));
          update_post_meta($post_id, 'contacts_others', esc_attr($_POST['contacts_others']));

      }
      return $post_id;
  }

  function view($content){
      $post = get_post();

      if('organizations' != $post->post_type) return $content;

      $email = get_post_meta($post->ID, 'email',true);
      $tel = get_post_meta($post->ID, 'tel',true);
      $contacts_others = get_post_meta($post->ID, 'contacts_others',true);

      ob_start();
      ?>
      <div class="org_contacts_cp section_cp">
          <ul>
              <?php
                  if($tel) echo "<li>Телефон:<br/>" . $tel . "</li>";
                  if($email) echo "<li>Email:<br/>" . $email . "</li>";
                  if($contacts_others) echo "<li>Прочие контакты:<br/>" . $contacts_others . "</li>";
              ?>
          </ul>
      </div>
      <?php
      $html = ob_get_contents();
      ob_get_clean();
      return $html . $content;
  }

/**
 * Служебные функции одиночки
 */
protected function __clone() {
	// ограничивает клонирование объекта
}
static public function getInstance() {
	if(is_null(self::$_instance))
	{
	self::$_instance = new self();
	}
	return self::$_instance;
}

} $OrganizationMeta_CP = OrganizationMeta_CP_Singleton::getInstance();
