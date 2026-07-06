<?php
    $g = fn($key) => $typography[$key] ?? '';
?>
<div class="typography-popup" data-field="<?php echo e($field); ?>" style="display:none;">
    <div class="typography-backdrop" style="position:fixed;inset:0;z-index:1040"></div>
    <div class="typography-panel" style="position:absolute;right:0;top:100%;z-index:1050;width:300px;background:#fff;border:1px solid #ccc;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,0.15);padding:12px;margin-top:4px;">
        <div class="mb-2">
            <label style="display:block;font-size:10px;font-weight:700;margin-bottom:2px;color:#666;">Font Family</label>
            <select name="typography[<?php echo e($field); ?>][font_family]" class="form-select form-select-sm typo-input">
                <option value="">Default</option>
                <?php $__currentLoopData = ['Inter, sans-serif','Arial, sans-serif','Helvetica, sans-serif','Georgia, serif','Times New Roman, serif','Courier New, monospace','Tahoma, sans-serif','Verdana, sans-serif','Trebuchet MS, sans-serif']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($ff); ?>" <?php echo e($g('font_family') === $ff ? 'selected' : ''); ?>><?php echo e(explode(',', $ff)[0]); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-2">
            <label style="display:block;font-size:10px;font-weight:700;margin-bottom:2px;color:#666;">Font Size</label>
            <div style="display:flex;gap:8px;align-items:center;">
                <input type="range" min="0.5" max="100" step="0.5" value="<?php echo e($g('font_size')); ?>" class="form-range flex-grow-1 typo-input" name="typography[<?php echo e($field); ?>][font_size]" style="height:6px;">
                <input type="text" value="<?php echo e($g('font_size')); ?>" class="form-control form-control-sm typo-input" name="typography[<?php echo e($field); ?>][font_size]" placeholder="Default" style="width:60px;text-align:center;">
            </div>
        </div>
        <div class="mb-2">
            <label style="display:block;font-size:10px;font-weight:700;margin-bottom:2px;color:#666;">Font Weight</label>
            <select name="typography[<?php echo e($field); ?>][font_weight]" class="form-select form-select-sm typo-input">
                <option value="">Default</option>
                <?php $__currentLoopData = ['100'=>'Thin','200'=>'Extra Light','300'=>'Light','400'=>'Normal','500'=>'Medium','600'=>'Semi Bold','700'=>'Bold','800'=>'Extra Bold','900'=>'Black']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($v); ?>" <?php echo e($g('font_weight') === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="row g-1 mb-2">
            <div class="col-4">
                <label style="display:block;font-size:10px;font-weight:700;margin-bottom:2px;color:#666;">Transform</label>
                <select name="typography[<?php echo e($field); ?>][text_transform]" class="form-select form-select-sm typo-input">
                    <option value="">-</option>
                    <?php $__currentLoopData = ['none','uppercase','lowercase','capitalize']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php echo e($g('text_transform') === $v ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-4">
                <label style="display:block;font-size:10px;font-weight:700;margin-bottom:2px;color:#666;">Style</label>
                <select name="typography[<?php echo e($field); ?>][font_style]" class="form-select form-select-sm typo-input">
                    <option value="">-</option>
                    <?php $__currentLoopData = ['normal','italic','oblique']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php echo e($g('font_style') === $v ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-4">
                <label style="display:block;font-size:10px;font-weight:700;margin-bottom:2px;color:#666;">Decoration</label>
                <select name="typography[<?php echo e($field); ?>][text_decoration]" class="form-select form-select-sm typo-input">
                    <option value="">-</option>
                    <?php $__currentLoopData = ['none','underline','line-through','overline']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php echo e($g('text_decoration') === $v ? 'selected' : ''); ?>><?php echo e($v); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="mb-2">
            <label style="display:block;font-size:10px;font-weight:700;margin-bottom:2px;color:#666;">Line Height</label>
            <div style="display:flex;gap:8px;align-items:center;">
                <input type="range" min="0.5" max="4" step="0.1" value="<?php echo e($g('line_height')); ?>" class="form-range flex-grow-1 typo-input" name="typography[<?php echo e($field); ?>][line_height]" style="height:6px;">
                <input type="text" value="<?php echo e($g('line_height')); ?>" class="form-control form-control-sm typo-input" name="typography[<?php echo e($field); ?>][line_height]" placeholder="Default" style="width:60px;text-align:center;">
            </div>
        </div>
        <div class="mb-2">
            <label style="display:block;font-size:10px;font-weight:700;margin-bottom:2px;color:#666;">Letter Spacing</label>
            <div style="display:flex;gap:8px;align-items:center;">
                <input type="range" min="-10" max="20" step="0.5" value="<?php echo e($g('letter_spacing')); ?>" class="form-range flex-grow-1 typo-input" name="typography[<?php echo e($field); ?>][letter_spacing]" style="height:6px;">
                <input type="text" value="<?php echo e($g('letter_spacing')); ?>" class="form-control form-control-sm typo-input" name="typography[<?php echo e($field); ?>][letter_spacing]" placeholder="Default" style="width:60px;text-align:center;">
            </div>
        </div>
        <div>
            <label style="display:block;font-size:10px;font-weight:700;margin-bottom:2px;color:#666;">Word Spacing</label>
            <div style="display:flex;gap:8px;align-items:center;">
                <input type="range" min="-10" max="30" step="0.5" value="<?php echo e($g('word_spacing')); ?>" class="form-range flex-grow-1 typo-input" name="typography[<?php echo e($field); ?>][word_spacing]" style="height:6px;">
                <input type="text" value="<?php echo e($g('word_spacing')); ?>" class="form-control form-control-sm typo-input" name="typography[<?php echo e($field); ?>][word_spacing]" placeholder="Default" style="width:60px;text-align:center;">
            </div>
        </div>
    </div>
</div>


<?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/invoices/partials/_typography-popup.blade.php ENDPATH**/ ?>