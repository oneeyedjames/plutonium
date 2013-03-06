<?php

$language =& Plutonium_Language::getInstance();

?><h3><?php echo $this->page->name; ?></h3>
<div><?php echo $this->page->body; ?></div>
<?php echo $language->translate('hello_world'); ?>