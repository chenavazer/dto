<?php
class MutateTypeDtoTest extends DtoTest\TestCase
{
    public function testNullableLocationReturnsNullWhenSetToNull()
    {
        $value = $this->callProtectedMethod(
            new \Dto\Dto([], ['x' => null], ['x' => ['type' => 'dto', 'class' => 'MutateTypeDtoChildDto', 'nullable' => true]]), 'mutateTypeDto',
            [null, 'x']
        );
        $this->assertNull($value);
    }
    
    public function testNotNullableLocationDoesNotReturnNullWhenSetToNull()
    {
        $value = $this->callProtectedMethod(
            new \Dto\Dto([], ['x' => null], ['x' => ['type' => 'dto', 'class' => 'MutateTypeDtoChildDto', 'nullable' => false]]), 'mutateTypeDto',
            [null, 'x']
        );
        $this->assertNotNull($value);
        $this->assertEquals('dog', $value->y);
    }
    
    public function testSettingPropertyWithDtoInstanceWorks()
    {
        $parent = new \Dto\Dto([], ['x' => null], ['x' => ['type' => 'dto', 'class' => 'MutateTypeDtoChildDto', 'nullable' => false]]);
        $child = new MutateTypeDtoChildDto();
        $child->y = 'my-value';
        
        $value = $this->callProtectedMethod($parent, 'mutateTypeDto', [$child, 'x']);
        
        $this->assertNotNull($value);
        $this->assertEquals($value->toArray(), $child->toArray());
    }
}

class MutateTypeDtoChildDto extends \Dto\Dto
{
    protected $template = [
        'y' => 'dog',
    ];
}