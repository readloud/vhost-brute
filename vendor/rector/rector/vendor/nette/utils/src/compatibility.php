<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace RectorPrefix20220323\Nette\Utils;

use RectorPrefix20220323\Nette;
if (\false) {
    /** @deprecated use Nette\HtmlStringable */
    interface IHtmlString extends \RectorPrefix20220323\Nette\HtmlStringable
    {
    }
} elseif (!\interface_exists(\RectorPrefix20220323\Nette\Utils\IHtmlString::class)) {
    \class_alias(\RectorPrefix20220323\Nette\HtmlStringable::class, \RectorPrefix20220323\Nette\Utils\IHtmlString::class);
}
namespace RectorPrefix20220323\Nette\Localization;

if (\false) {
    /** @deprecated use Nette\Localization\Translator */
    interface ITranslator extends \RectorPrefix20220323\Nette\Localization\Translator
    {
    }
} elseif (!\interface_exists(\RectorPrefix20220323\Nette\Localization\ITranslator::class)) {
    \class_alias(\RectorPrefix20220323\Nette\Localization\Translator::class, \RectorPrefix20220323\Nette\Localization\ITranslator::class);
}
