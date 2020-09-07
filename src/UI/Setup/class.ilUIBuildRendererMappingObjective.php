<?php

namespace ILIAS\UI\Setup;

use ILIAS\Setup;
use ILIAS\UI\Implementation\Render\ComponentRenderer;
use ILIAS\UI\Implementation\Render\JavaScriptBinding;
use ILIAS\UI\Implementation\Render\TemplateFactory;
use ILIAS\UI\Implementation\Render\ilTemplateWrapperFactory;
use ILIAS\UI\Implementation\Factory as UIFactory;
use ILIAS\Refinery\Factory as RefineryFactory;
use ilLanguage;
use ILIAS\UI\Implementation\Render\ilJavaScriptBinding;

/**
 * Class ilGSBootLoaderBuilder
 *
 * @package ILIAS\GlobalScreen\BootLoader
 */
class ilUIBuildRendererMappingObjective extends Setup\Artifact\BuildArtifactObjective
{
    const ARTIFACT_PATH = 'src/UI/artifacts/core_renderers.php';

    /**
     * @var	UIFactory
     */
    private $ui_factory;

    /**
     * @var	TemplateFactory
     */
    private $tpl_factory;

    /**
     * @var	ilLanguage
     */
    private $lng;

    /**
     * @var	JavaScriptBinding
     */
    private $js_binding;

    /**
     * @var RefineryFactory
     */
    private $refinery;

    public function getArtifactPath() : string
    {
        return self::ARTIFACT_PATH;
    }


    public function build() : Setup\Artifact
    {
        $this->ui_factory = new class() extends UIFactory { public function __construct() {} };
        $this->tpl_factory = new class() extends ilTemplateWrapperFactory { public function __construct() {} };
        $this->lng = new class() extends ilLanguage { public function __construct() {} };
        $this->js_binding = new class() extends ilJavaScriptBinding { public function __construct() {} };
        $this->refinery = new class() extends RefineryFactory { public function __construct() {} };

        $interface_name = ComponentRenderer::class;
        $interface_finder = new Setup\ImplementationOfInterfaceFinder($interface_name);

        $mapping = [];

        foreach ($interface_finder->getMatchingClassNames() as $renderer_class)
        {
            $renderer = $this->createRenderer($renderer_class);

            foreach ($renderer->getComponentInterfaceName() as $component_class) {
                $mapping[$component_class] = $renderer_class;
            }
        }

        return new Setup\Artifact\ArrayArtifact($mapping);
    }

    /**
     * @param string $renderer_class
     * @return ComponentRenderer
     */
    private function createRenderer(string $renderer_class) : ComponentRenderer
    {
        return new $renderer_class(
            $this->ui_factory,
            $this->tpl_factory,
            $this->lng,
            $this->js_binding,
            $this->refinery
            );
    }
}
