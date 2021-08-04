{% extends "base.html" %}

{% block title %}Add income{% endblock %}

{% block body %}

{% if incomes.errors is not empty %}
    <p>Errors:</p>
        <ul>
            {% for error in incomes.errors %}
                <li>{{ error }}</li>
            {% endfor %}
        </ul>
{% endif %} 

<main>
    <div class="container-fluid">

        <div class="mainbg">
        
            <div class="row head spacer"> 
                <div class="col">           

                    <div class="d-inline-block">
                        <a href="/items/index" class="btn mmbtn" role="button">Menu główne</a>
                    </div>

                    <a href="/logout" class="button" style="float: right; margin-top: 20px; margin-right: 20px">Wyloguj się
                    </a>
                </div>   
            </div> 

            <div class="col title">
                <h1>Nowy przychód </h1>
            </div>

            <form action="/incomes/create" method="post">

                <div class="form-group row">
                    <label for="value" class="col-5 col-form-label"><h5>Kwota</h5></label>
                    <div class="col-7" >
                        <input type="number" name="amount" step="0.01" min="0" required></label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date" class="col-5 col-form-label"><h5>Data</h5></label>
                    <div class="col-7" >
                        <input type="date" name="date" id="theDate" required ></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="incomecat" class="col-5 col-form-label"><h5>Kategoria</h5></label>
                    <div class="col-7" >
                        <select id="incomecat" name="incomecat">
                            {% for incomeCategory in incomesCategories %}
                                <option> {{ incomeCategory }} </option>
                            {% endfor %}  
                        </select>
                        <button id="edit-button" data-toggle="modal">Edycja</button>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="comment" class="col-5 col-form-label"><h5>Komentarz (opcjonalnie)</h5></label>
                    <div class="col-7" >
                        <textarea name="comment" id="comment" rows="1"></textarea>
                    </div>
                </div>

                <div class="form-group row addExpense">
                    <div class="col d-flex justify-content-center">
                        <button type="submit" class="btn sbmbtn">Dodaj</button>
                    </div>
                </div>
        
            </form>	

        </div>
    </div>
</main>



<!-- Modal change category value -->
<div class="modal" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edycja przychodu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="close" aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" id="insert_form">
                    <div>
                        <label class="title" for="incomeCategory"><p>Kategoria przychodu:</p></label>
                        <textarea name="incomeCategory" id="incomeCategory" rows="1"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cancelChanges" data-dismiss="modal">Anuluj</button>
                        <button type="submit" id="insert" class="btn btn-primary acceptChanges">Zapisz zmiany</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{% endblock %}

{% block scripts %}
<script>

    // Get the modal
    var modal = document.getElementById("myModal");
    
    // Get the button that opens the modal
    var btn = document.getElementById("edit-button");
    
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    
    // When the user clicks on the button, open the modal
    btn.onclick = function() {
      modal.style.display = "block";
    }
    
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }
    
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    $(document).ready(function(){
        $('#insert_form').on("submit", function(event){
            event.preventDefault();
            if($('#incomeCategory').val() == '') 
            {
                alert("Wprowadź nową kategorię!");
            } 
            else 
            {
                $.ajax({
                    url: "insert.php",
                    method: "POST",
                    data: $('#insert_form').serialize(),
                    beforeSend:function(){  
                        $('#insert').val("Inserting");  
                        },  
                    success: function(data) {
                        $('#insert_form')[0].reset();
                        $('#myModal').modal('hide');
                        $('#incomecat').html(data);
                    }
                });
            }
        }
    });
</script>

{% endblock %}

