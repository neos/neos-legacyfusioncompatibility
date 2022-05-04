<?php
declare(strict_types=1);
namespace Neos\Neos\LegacyFusionCompatibility\TypeConverter;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\ContentRepository\SharedModel\VisibilityConstraints;
use Neos\ContentRepository\Projection\Content\ContentGraphInterface;
use Neos\ContentRepository\Projection\Content\ContentSubgraphInterface;
use Neos\ContentRepository\SharedModel\Workspace\ContentStreamIdentifier;
use Neos\ContentRepository\DimensionSpace\DimensionSpace\DimensionSpacePoint;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\PropertyMappingConfigurationInterface;
use Neos\Flow\Property\TypeConverter\AbstractTypeConverter;

/**
 * An Object Converter for content subgraphs
 *
 * @Flow\Scope("singleton")
 */
class ContentSubgraphConverter extends AbstractTypeConverter
{
    /**
     * @Flow\Inject
     * @var ContentGraphInterface
     */
    protected $contentGraph;

    /**
     * @var array<int,string>
     */
    protected $sourceTypes = ['string'];

    /**
     * @var string
     */
    protected $targetType = ContentSubgraphInterface::class;

    /**
     * @var int
     */
    protected $priority = 1;

    /**
     * @param string $source
     * @param string $targetType
     * @param array<string,mixed> $convertedChildProperties
     */
    public function convertFrom(
        mixed $source,
        $targetType,
        array $convertedChildProperties = [],
        PropertyMappingConfigurationInterface $configuration = null
    ): ContentSubgraphInterface {
        $sourceArray = json_decode($source, true);

        return $this->contentGraph->getSubgraphByIdentifier(
            ContentStreamIdentifier::fromString($sourceArray['contentStreamIdentifier']),
            DimensionSpacePoint::fromArray($sourceArray['dimensionSpacePoint']['coordinates']),
            VisibilityConstraints::withoutRestrictions()
        );
    }
}
