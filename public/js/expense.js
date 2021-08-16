document.getElementById('amount').addEventListener('input', getCategoryLimit);
//document.getElementById('date').addEventListener('input', getSumOfExpenses);
document.getElementById('expensecat').addEventListener('change', getCategoryLimit);


let category = $('#expensecat').val();

console.log(category);

let controller = '/Expenses/getCategoryLimit';

function getCategoryLimit() {
    fetch('/Expenses/getCategoryLimit', {
        method: 'POST',
        body: category,
        headers: category ? { 'Content-Type': 'text'} : {}
    })
    .then(responseData => {
    console.log(responseData);
});
};











/*$(document).ready(function() {

    $('select#expensecat').on('change', function() {
        let date = new Date($('#date').val());
    
        let month = date.getMonth() + 1;
        if (month < 10) month = "0" + month;
    
        let url = '/expenses/getExpenseSummary/' + date.getFullYear() + '-' + month;
    
        fetch(url)
            .then(response => response.json())
            .then(data => {
                let category = $('select#expensecat').children('option:selected').val();
                expenseTable.forEach(element => {
                    if(element['name'] == category) {
                        if(element['expense_limit'] == null) {
                            $('#limitBox').hide(500);
                        } else {
                            $('#limitBox').show(500);
                            $('#limitValue').html(element['expense_limit']);
                            $('#issuedValue').html(element['summary']);
                            $('#leftToTheLimit').html(element['expense_limit'] - element['summary']);
                            $('#afterOperation').html(element['expense_limit'] - element['summary'] - $('#amount').val());
                        }
                    }
                });
            })
    });
    $('input#amount').on('input', function() {
        let date = new Date($('#date').val());
    
        let month = date.getMonth() + 1;
        if (month < 10) month = "0" + month;
    
        let url = '/expenses/getExpenseSummary/' + date.getFullYear() + '-' + month;
    
        fetch(url)
            .then(response => response.json())
            .then(data => {
                let category = $('select#expensecat').children('option:selected').val();
                expenseTable.forEach(element => {
                    if(element['name'] == category) {
                        if(element['expense_limit'] == null) {
                            $('#limitBox').hide(500);
                        } else {
                            $('#limitBox').show(500);
                            $('#limitValue').html(element['expense_limit']);
                            $('#issuedValue').html(element['summary']);
                            $('#leftToTheLimit').html(element['expense_limit'] - element['summary']);
                            $('#afterOperation').html(element['expense_limit'] - element['summary'] - $('#amount').val());
                        }
                    }
                });
            })
    });
    $('input#date').on('change', function() {
        let date = new Date($('#date').val());
    
        let month = date.getMonth() + 1;
        if (month < 10) month = "0" + month;
    
        let url = '/expenses/getExpenseSummary/' + date.getFullYear() + '-' + month;
    
        fetch(url)
            .then(response => response.json())
            .then(data => {
                let category = $('select#expensecat').children('option:selected').val();
                expenseTable.forEach(element => {
                    if(element['name'] == category) {
                        if(element['expense_limit'] == null) {
                            $('#limitBox').hide(500);
                        } else {
                            $('#limitBox').show(500);
                            $('#limitValue').html(element['expense_limit']);
                            $('#issuedValue').html(element['summary']);
                            $('#leftToTheLimit').html(element['expense_limit'] - element['summary']);
                            $('#afterOperation').html(element['expense_limit'] - element['summary'] - $('#amount').val());
                        }
                    }
                });
            })
    });
});*/