<div class="styleguide-example">
    <h3>
        <span class="styleguide-section"><?php echo $section->getSection(); ?></span>
        <span class="styleguide-title"><?php echo $section->getTitle(); ?></span>
        <span class="styleguide-filename"><?php echo $section->getFilename(); ?></span>
    </h3>

    <div class="styleguide-description">
        <p><?php echo nl2br($section->getDescription()); ?></p>
        <?php
            if (count($section->getModifiers()) > 0) {
        ?>
            <ul class="styleguide-modifier">
                <?php foreach ($section->getModifiers() as $modifier) { ?>
                    <li>
                        <span class="styleguide-modifier-name <?php ($modifier->isExtender()) ? 'styleguide-extender-name' : ''; ?>">
                            <?php echo $modifier->getName(); ?>
                        </span>
                            <?php if ($modifier->isExtender()) { ?>
                                @extend
                                <span class="styleguide-modifier-name"><?php echo $modifier->getExtendedClass(); ?></span>
                            <?php } ?>
                        <?php if (!empty($modifier->getDescription)) { ?>
                            - <?php echo $modifier->getDescription(); ?>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>

    <div class="styleguide-elements">
        <div class="styleguide-element">
            <?php echo str_replace('$modifierClass', '', $section->getMarkup()); ?>
        </div>
        <?php foreach ($section->getModifiers() as $modifier) { ?>
            <div class="styleguide-element styleguide-modifier <?php ($modifier->isExtender()) ? 'styleguide-extender' : ''; ?>">
                <span class="styleguide-modifier-label <?php ($modifier->isExtender()) ? 'styleguide-extender-label' : ''; ?>"><?php echo  $modifier->getName(); ?></span>
                <?php echo $modifier->getExampleHtml(); ?>
            </div>
        <?php } ?>
    </div>

    <div class="styleguide-html">
        <pre class="styleguide-code"><code><?php echo htmlentities($section->getMarkup()); ?></code></pre>
    </div>
</div>
