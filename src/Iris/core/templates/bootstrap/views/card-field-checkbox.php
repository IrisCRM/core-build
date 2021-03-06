<?php
/**
 * Поле-чекбокс
 */
?>
<div class="form-group<?php echo $data['mandatory'] ? ' required' : ''; ?> col-sm-<?php echo $data['fieldwidth']; ?>">
  <?php if (empty($data['hide_label'])) : ?>
    <label for="<?php echo $data['code']; 
      ?>" class="col-sm-<?php echo $data['labelwidth']; ?> no-horizontal-padding control-label<?php 
      echo $data['title'] ? ' card_elem_title"' : '';
      ?>"<?php echo $data['title'] ? ' title="' . $data['title'] . '"' : '';
      ?>><?php echo $data['caption']; ?></label>
  <?php endif; ?>
  <div class="col-sm-<?php echo $data['controlwidth']; ?> no-horizontal-padding flexbox-container flexbox-justify-center control-label">
    <input class="form-check-input" type="checkbox" <?php 
      echo $data['template_prefix']; ?>id="<?php echo $data['code']; 
      ?>" <?php echo $data['value'] ? ' checked' : ''; 
      ?> <?php echo $data['attributes']; 
      ?>/>
    <?php echo $data['hidden']; ?>
  </div>
</div>