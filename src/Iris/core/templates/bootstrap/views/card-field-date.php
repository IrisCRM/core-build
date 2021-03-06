<?php
/**
 * Поле-дата
 */
?>
<div class="form-group<?php echo $data['mandatory'] ? ' required' : ''; ?> col-sm-<?php echo $data['fieldwidth']; ?>">
  <?php if (empty($data['hide_label'])) : ?>
    <label for="<?php echo $data['code'];
      ?>" class="col-sm-<?php echo !empty($data['labelwidth']) ? $data['labelwidth'] : 2; ?> no-horizontal-padding control-label<?php 
      echo $data['title'] ? ' card_elem_title"' : '';
      ?>"<?php echo $data['title'] ? ' title="' . $data['title'] . '"' : '';
      ?>><?php echo $data['caption']; ?></label>
  <?php endif; ?>
  <div class="col-sm-<?php echo $data['controlwidth']; ?> no-horizontal-padding">
    <div class="input-group input-group-sm">
      <input class="form-control edtText" style="width: 100%" type="text" <?php echo $data['template_prefix']; ?>id="<?php echo $data['code'];
        ?>" maxlength="<?php echo $data['maxlength'];
        ?>" value="<?php echo $data['value'];
        ?>" <?php echo $data['attributes']; ?>/><?php
        echo $data['hidden'];
      ?>
      <span class="input-group-btn">
        <button class="btn btn-default btn-sm button" <?php
          ?> onclick="new CalendarDateSelect($(this).parentNode.parentNode.getElementsByTagName('input')[0], { time: <?php
          echo $data['with_time'] ? 'true' : 'false'; ?>, buttons: <?php
          echo $data['with_time'] ? 'true' : 'false'; ?>, embedded: false, year_range: 10 } );">
          <span class="glyphicon glyphicon-calendar"></span>
        </button>
      </span>
    </div>
  </div>
</div>