<?php

declare(strict_types=1);

use yii\helpers\Url;

class AboutCest
{
    public function ensureThatAboutWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/about'));
        $I->see('About', 'h1');
    }
}
