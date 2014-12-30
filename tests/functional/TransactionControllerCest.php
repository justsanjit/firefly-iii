<?php

/**
 * Class TransactionControllerCest
 */
class TransactionControllerCest
{
    /**
     * @param FunctionalTester $I
     */
    public function _after(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedAs(['email' => 'thegrumpydictator@gmail.com', 'password' => 'james']);
    }

    public function create(FunctionalTester $I)
    {
        $I->wantTo('create a transaction');
        $I->amOnPage('/transactions/create/withdrawal?account_id=1');
        $I->see('Add a new withdrawal');
    }

    public function delete(FunctionalTester $I)
    {
        $I->wantTo('delete a transaction');
        $I->amOnPage('/transaction/delete/3');
        $I->see('Delete withdrawal "Huur Portaal for January 2014"');
    }

    public function destroyWithdrawal(FunctionalTester $I)
    {
        $I->wantTo('destroy a withdrawal');
        $I->amOnPage('/transaction/delete/3');
        $I->submitForm('#destroy', []);
        $I->see('Transaction &quot;Huur Portaal for January 2014&quot; destroyed.');

    }

    public function destroyDeposit(FunctionalTester $I)
    {
        $I->wantTo('destroy a deposit');
        $I->amOnPage('/transaction/delete/32');
        $I->submitForm('#destroy', []);
        $I->see('Transaction &quot;Salary&quot; destroyed.');

    }

    public function destroyTransfer(FunctionalTester $I)
    {
        $I->wantTo('destroy a transfer');
        $I->amOnPage('/transaction/delete/406');
        $I->submitForm('#destroy', []);
        $I->see('Transaction &quot;Money for new PC&quot; destroyed.');

    }

    public function edit(FunctionalTester $I)
    {
        $I->wantTo('edit a transaction');
        $I->amOnPage('/transaction/edit/408');
        $I->see('Edit transfer &quot;Money for piggy&quot;');
    }

    public function index(FunctionalTester $I)
    {
        $I->wantTo('see all withdrawals');
        $I->amOnPage('/transactions/withdrawal');
        $I->see('Expenses');
    }

    public function indexExpenses(FunctionalTester $I)
    {
        $I->wantTo('see all expenses');
        $I->amOnPage('/transactions/deposit');
        $I->see('Revenue, income and deposits');
    }

    public function indexTransfers(FunctionalTester $I)
    {
        $I->wantTo('see all transfers');
        $I->amOnPage('/transactions/transfers');
        $I->see('Transfers');
    }

    public function show(FunctionalTester $I)
    {
        $I->wantTo('see a transaction');
        $I->amOnPage('/transaction/show/406');
        $I->see('Transfer "Money for new PC"');
        $I->see('1.259');
    }

    public function store(FunctionalTester $I)
    {
        $I->wantTo('store a transaction');
        $I->amOnPage('/transactions/create/withdrawal');
    }

    public function update(FunctionalTester $I)
    {
        $I->wantTo('update a transaction');
        $I->amOnPage('/transaction/edit/3');
        $I->see('Huur Portaal for January 2014');
        $I->submitForm(
            '#update', [
                         'description'        => 'Huur Portaal for January 2014!',
                         'account_id'         => 1,
                         'expense_account'    => 'Portaal',
                         'amount'             => 500,
                         'date'               => '2014-01-01',
                         'budget_id'          => 2,
                         'category'           => 'House',
                         'post_submit_action' => 'update'
                     ]
        );
        $I->see('Huur Portaal for January 2014!');
    }


}