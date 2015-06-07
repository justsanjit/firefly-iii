<?php
use FireflyIII\Models\Preference;
use FireflyIII\Models\TransactionCurrency;
use FireflyIII\Support\Amount;
use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * Generated by PHPUnit_SkeletonGenerator on 2015-05-05 at 16:33:55.
 */
class AmountSupportTest extends TestCase
{
    /**
     * @var Amount
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        parent::setUp();
        $this->object = new Amount;
        $user         = FactoryMuffin::create('FireflyIII\User');
        $this->be($user);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers FireflyIII\Support\Amount::format
     * @covers FireflyIII\Support\Amount::getCurrencySymbol
     */
    public function testFormat()
    {
        $amount = '123';
        $result = $this->object->format($amount, true);
        $this->assertTrue(str_contains($result, $amount));
    }

    /**
     * @covers FireflyIII\Support\Amount::formatJournal
     */
    public function testFormatJournalColouredTransfer()
    {


        FactoryMuffin::create('FireflyIII\Models\TransactionType'); // withdrawal
        FactoryMuffin::create('FireflyIII\Models\TransactionType'); // deposit
        /** @var \FireflyIII\Models\TransactionJournal $journal */
        $journal = FactoryMuffin::create('FireflyIII\Models\TransactionJournal');
        $symbol  = $journal->transactionCurrency->symbol;

        $result = $this->object->formatJournal($journal, true);

        // transfer is blue:
        $this->assertTrue(str_contains($result, '<span class="text-info">'));
        // transfer contains currency code:
        $this->assertTrue(str_contains($result, $symbol));
        // all amounts are 100.
        $this->assertTrue(str_contains($result, '100'));
    }

    /**
     * @covers FireflyIII\Support\Amount::formatJournal
     */
    public function testFormatJournalUncolouredTransfer()
    {
        FactoryMuffin::create('FireflyIII\Models\TransactionType'); // withdrawal
        FactoryMuffin::create('FireflyIII\Models\TransactionType'); // deposit
        /** @var \FireflyIII\Models\TransactionJournal $journal */
        $journal = FactoryMuffin::create('FireflyIII\Models\TransactionJournal');
        $symbol  = $journal->transactionCurrency->symbol;

        $result = $this->object->formatJournal($journal, false);

        // transfer is not blue:
        $this->assertFalse(str_contains($result, '<span class="text-info">'));
        // transfer contains currency code:
        $this->assertTrue(str_contains($result, $symbol));
        // all amounts are 100.
        $this->assertTrue(str_contains($result, '100'));
    }

    /**
     * @covers FireflyIII\Support\Amount::formatJournal
     */
    public function testFormatJournalWithSymbol()
    {
        FactoryMuffin::create('FireflyIII\Models\TransactionType'); // withdrawal
        /** @var \FireflyIII\Models\TransactionJournal $journal */
        $journal         = FactoryMuffin::create('FireflyIII\Models\TransactionJournal');
        $symbol          = $journal->transactionCurrency->symbol;
        $journal->symbol = $symbol;

        $result = $this->object->formatJournal($journal, true);

        // transfer is not blue:
        $this->assertFalse(str_contains($result, '<span class="text-danger">'));
        // transfer contains currency code:
        $this->assertTrue(str_contains($result, $symbol));
        // all amounts are 100.
        $this->assertTrue(str_contains($result, '100'));
    }

    /**
     * @covers FireflyIII\Support\Amount::formatJournal
     */
    public function testFormatJournalWithdrawal()
    {
        /** @var \FireflyIII\Models\TransactionJournal $journal */
        $journal = FactoryMuffin::create('FireflyIII\Models\TransactionJournal');
        $symbol  = $journal->transactionCurrency->symbol;

        $result = $this->object->formatJournal($journal, true);

        // transfer is not blue:
        $this->assertFalse(str_contains($result, '<span class="text-success">'));
        // transfer contains currency code:
        $this->assertTrue(str_contains($result, $symbol));
        // all amounts are 100.
        $this->assertTrue(str_contains($result, '100'));
    }

    /**
     * @covers FireflyIII\Support\Amount::formatTransaction
     */
    public function testFormatTransaction()
    {
        // is a withdrawal.
        $transaction         = FactoryMuffin::create('FireflyIII\Models\Transaction');
        $transaction->amount = -100;
        $transaction->save();
        $result = $this->object->formatTransaction($transaction, true);


        // withdrawal is red:
        $this->assertTrue(str_contains($result, '<span class="text-danger">'));
        // all amounts are 100.
        $this->assertTrue(str_contains($result, '100'));
    }

    /**
     * @covers FireflyIII\Support\Amount::formatWithSymbol
     */
    public function testFormatWithSymbolColouredAboveZero()
    {
        $amount   = 123;
        $symbol   = 'top';
        $coloured = true;

        $result = $this->object->formatWithSymbol($symbol, $amount, $coloured);

        // has colour:
        $this->assertTrue(str_contains($result, '<span class="text-success">'));
        // has symbol:
        $this->assertTrue(str_contains($result, $symbol));
        // has amount:
        $this->assertTrue(str_contains($result, '123'));
    }

    /**
     * @covers FireflyIII\Support\Amount::formatWithSymbol
     */
    public function testFormatWithSymbolColouredBelowZero()
    {
        $amount   = -123;
        $symbol   = 'top';
        $coloured = true;

        $result = $this->object->formatWithSymbol($symbol, $amount, $coloured);

        // has colour:
        $this->assertTrue(str_contains($result, '<span class="text-danger">'));
        // has symbol:
        $this->assertTrue(str_contains($result, $symbol));
        // has amount:
        $this->assertTrue(str_contains($result, '123'));
    }

    /**
     * @covers FireflyIII\Support\Amount::formatWithSymbol
     */
    public function testFormatWithSymbolColouredZero()
    {
        $amount   = 0.0;
        $symbol   = 'top';
        $coloured = true;

        $result = $this->object->formatWithSymbol($symbol, $amount, $coloured);

        // has colour:
        $this->assertTrue(str_contains($result, '#999'));
        // has symbol:
        $this->assertTrue(str_contains($result, $symbol));
        // has amount:
        $this->assertTrue(str_contains($result, '0'));
    }

    /**
     * @covers FireflyIII\Support\Amount::formatWithSymbol
     */
    public function testFormatWithSymbolNotColoured()
    {
        $amount   = 0;
        $symbol   = 'top';
        $coloured = false;

        $result = $this->object->formatWithSymbol($symbol, $amount, $coloured);

        // has symbol:
        $this->assertTrue(str_contains($result, $symbol));
        // has amount:
        $this->assertTrue(str_contains($result, '0'));
    }

    /**
     * @covers FireflyIII\Support\Amount::getAllCurrencies
     */
    public function testGetAllCurrencies()
    {
        $size = TransactionCurrency::count();
        $list = $this->object->getAllCurrencies();
        $this->assertCount($size, $list);
    }

    /**
     * @covers FireflyIII\Support\Amount::getCurrencyCode
     */
    public function testGetCurrencyCode()
    {
        $code = $this->object->getCurrencyCode();
        $this->assertEquals('EUR', $code);
    }

    /**
     * @covers FireflyIII\Support\Amount::getCurrencyCode
     */
    public function testGetCurrencyCodeNoSuchCurrency()
    {
        $user = FactoryMuffin::create('FireflyIII\User');
        $this->be($user);

        // delete any currency preferences:
        Preference::where('user_id', $user->id)->delete();

        // delete transaction currencies:
        foreach (TransactionCurrency::get() as $c) {
            $c->delete();
        }

        $preference          = FactoryMuffin::create('FireflyIII\Models\Preference');
        $preference->user_id = $user->id;
        $preference->name    = 'currencyPreference';
        $preference->data    = 'SOM';
        $preference->save();

        Preferences::shouldReceive('get')->withArgs(['currencyPreference', 'EUR'])->andReturn($preference);

        $code = $this->object->getCurrencyCode();
        $this->assertEquals('EUR', $code);
    }

    /**
     * @covers FireflyIII\Support\Amount::getCurrencySymbol
     */
    public function testGetCurrencySymbol()
    {
        // will the the euro:
        $eur = TransactionCurrency::whereCode('EUR')->first();

        $result = $this->object->getCurrencySymbol();
        $this->assertEquals($eur->symbol, $result);
    }

    /**
     * @covers FireflyIII\Support\Amount::getDefaultCurrency
     */
    public function testGetDefaultCurrency()
    {
        // will the the euro:
        $eur = TransactionCurrency::whereCode('EUR')->first();

        $result = $this->object->getDefaultCurrency();
        $this->assertEquals($eur->id, $result->id);
    }


}