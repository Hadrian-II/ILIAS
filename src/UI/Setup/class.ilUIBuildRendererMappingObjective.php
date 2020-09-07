<?php

namespace ILIAS\UI\Setup;

use ILIAS\Setup;
use ILIAS\UI\Implementation\Render\ComponentRenderer;

/**
 * Class ilGSBootLoaderBuilder
 *
 * @package ILIAS\GlobalScreen\BootLoader
 */
class ilUIBuildRendererMappingObjective extends Setup\Artifact\BuildArtifactObjective
{
    const ARTIFACT_PATH = 'src/UI/artifacts/core_renderers.php';

    public function getArtifactPath() : string
    {
        return self::ARTIFACT_PATH;
    }


    public function build() : Setup\Artifact
    {
        $interface_name = ComponentRenderer::class;
        $interface_finder = new Setup\ImplementationOfInterfaceFinder($interface_name);
        $class_names = iterator_to_array($interface_finder->getMatchingClassNames());

        return new Setup\Artifact\ArrayArtifact($class_names);
    }
}
