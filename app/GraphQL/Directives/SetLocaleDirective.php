<?php declare(strict_types=1);

namespace App\GraphQL\Directives;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\App;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class SetLocaleDirective extends BaseDirective implements FieldMiddleware
{

    public function name(): string
    {
        return 'setLocale';
    }

    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
        directive @setLocale on FIELD_DEFINITION
        GRAPHQL;
    }

    /**
     * Wrap around the final field resolver.
     *
     * @param  \Nuwave\Lighthouse\Schema\Values\FieldValue  $fieldValue
     */
    public function handleField(FieldValue $fieldValue): void
    {

        $fieldValue->wrapResolver(fn (callable $resolver) => function (mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver) {

            if (isset($args['locale'])) {
                App::setLocale($args['locale']);
            }

            $result = $resolver($root, $args, $context, $resolveInfo);

            return $result;
        });
    }
}
