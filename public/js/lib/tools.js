/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function currency(price)
{
    var sPrice = price.split('.');
    return sPrice[0] + '.' + _processDecimals(sPrice[1]);
}

function _processDecimals(decimals)
{
    if (typeof decimals == 'undefined')
    {
        decimals = '00';
    }

    if (decimals.length > 2)
    {
        decimals = decimals.substring(0, 2);
    }

    while (decimals.length < 2)
    {
        decimals += '0';
    }

    return decimals;
}

//function test()
//{
//    alert(currency('0'));
//    alert(currency('1'));
//    alert(currency('1.00003'));
//    alert(currency('1234'));
//    alert(currency('1234.1'));
//    alert(currency('1234.12'));
//    alert(currency('1234.123'));
//}