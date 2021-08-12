document.getElementById('amount').addEventListener('input', getCategoryLimit);
//document.getElementById('date').addEventListener('input', getSumOfExpenses);
document.getElementById('expensecat').addEventListener('change', getCategoryLimit);

let category = $('#expensecat').val();

console.log(category);

const sendHttpRequest = (method, url, data) => {
    return fetch(url, {
      method: method,
      body: JSON.stringify(data),
      headers: data ? { 'Content-Type': 'application/json' } : {}
    }).then(response => {
      if (response.status >= 400) {
        // !response.ok
        return response.json().then(errResData => {
          const error = new Error('Something went wrong!');
          error.data = errResData;
          throw error;
        });
      }
      return response.json();
    });
  };
  
  let controller = '/addexpense/' + category;

    function getCategoryLimit() {
    sendHttpRequest('GET', controller).then(responseData => {
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