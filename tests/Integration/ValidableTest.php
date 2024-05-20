<?php

namespace Padosoft\Laravel\Validable\Test\Integration;

use PHPUnit\Framework\Attributes\Test;

class ValidableTest extends TestCase
{


    #[Test]
    public function testNotSaveInvalid()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $model = new TestModel();
        $model->order = 1;
        $ret = $model->save();
        $this->assertEquals($ret, false);
        $this->assertEquals($model->hasErrors(), true);
        $this->assertEquals(count($model->getErrors()), 1);
        $this->assertDatabaseMissing('test_models', [
            'name' => 'test'
        ]);
    }

    #[Test]
    public function testSaveValidAttributesWithHiddenAttributes()
    {
        $model = new TestModelWithHiddenAttributes();
        $model->name = 'test';
        //$model->order = 1;
        $ret = $model->save();
        $this->assertDatabaseHas('test_models', [
            'name' => 'test'
        ]);
        $this->assertEquals($ret, true);
        $this->assertEquals($model->hasErrors(), false);
    }

    #[Test]
    public function testSaveValidDatesAttributes()
    {
        $model = new TestModelWithDates();
        $model->name = 'test';
        $model->date_test = date('Y-m-d');
        $model->datetime_test = date('Y-m-d H:i:s');
        //$model->order = 1;
        $ret = $model->save();
        $this->assertDatabaseHas('test_models_with_dates', [
            'name' => 'test'
        ]);

        $this->assertEquals($ret, true);
        $this->assertEquals($model->hasErrors(), false);
    }

    #[Test]
    public function testSaveValid()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $model = new TestModel();
        $model->name = 'test';
        //$model->order = 1;
        $ret = $model->save();
        $this->assertDatabaseHas('test_models', [
            'name' => 'test'
        ]);
        $this->assertEquals($ret, true);
        $this->assertEquals($model->hasErrors(), false);
    }

    #[Test]
    public function testNotSaveInvalidNotMandatory()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $model = new TestModel();
        $model->name = 'test';
        $model->order = 'a';
        $ret = $model->save();
        $this->assertEquals($ret, false);
        $this->assertEquals($model->hasErrors(), true);
        $this->assertEquals(count($model->getErrors()), 1);
        $this->assertDatabaseMissing('test_models', [
            'name' => 'test'
        ]);
    }

    #[Test]
    public function testCanGetRulesAndMessagesOfAModel()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $rules = TestModel::getRules();
        $messages = TestModel::getMessages();
        $this->assertEquals(count($rules), 2);
        $this->assertEquals(count($messages), 1);

    }

    #[Test]
    public function testCanGetRulesSpecificRulesOfAModel()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $model = new TestModel();
        $rules = TestModelWithSpecificRules::getRules();
        $CreatingRules = TestModelWithSpecificRules::getCreatingRules();
        $UpdatingRules = TestModelWithSpecificRules::getUpdatingRules($model);

        $this->assertEquals($rules, $CreatingRules);
        $this->assertNotEquals($rules, $UpdatingRules);

    }

    #[Test]
    public function testUpdateRulesWithReplacement()
    {
        /*
        $model = TestModel::create();
        $model->name = 'test';
        $model->save();
        */
        $model = new TestModelWithSpecificRules();
        $model->name = 'test';
        $save = $model->save();

        $model->order = 1;
        $update = $model->save();

        $model2 = new TestModelWithSpecificRules();
        $model2->name = 'test';
        $save2 = $model2->save();

        $this->assertEquals($save, true);
        $this->assertEquals($update, true);
        $this->assertEquals($save2, false);


    }


}
