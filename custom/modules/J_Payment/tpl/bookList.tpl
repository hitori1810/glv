<div style="margin-left: 10%;">

<div id="book_list" class="content" >

    <div class="row" style="    margin-left: 6%;">
        <div class="col-md-12"><button type="button" id="btnAddBook" class="btn btn-primary">Add Row</button></div>
    </div>

    <div style="display: flex;margin: 10px 0px;text-align:center;">
        <div class="col-md-2 book_column">
            <b>Book Name</b><span class="required">*</span>
        </div>
        <div class="col-md-1 book_column">
            <b>Quantity</b><span class="required">*</span>
        </div>
        <div class="col-md-2 book_column">
            <b>Price</b>
        </div>
        <div class="col-md-2 book_column">
            <b>Amount</b>
        </div>
        <div class="col-md-2 book_column">
        </div>
    </div>

    <div class="bookItem" style="display:none;">
        <div class="col-md-2 book_column">
            <select class="book_select" name="book_select[]" width="140px" onchange="selectBook($(this))">{$OPTIONS}</select>
        </div>
        <div class="col-md-1 book_column">
            <input type="text" class="book_quantity" name="book_quantity[]" size="2" maxlength="100" value="1" style="text-align: right;" onchange="caculatePaymentBookGift()"></input>
        </div>
        <div class="col-md-2 book_column">
            <input type="text" class="book_price input_readonly" name="book_price[]" size="20" maxlength="100" readonly></input>
        </div>
        <div class="col-md-2 book_column ">
            <input type="text" class="book_amount input_readonly" name="book_amount[]" size="20" maxlength="100" readonly></input>
        </div>
        <div class="col-md-2 book_column">
            <input type="button" class="book_remove" value="Remove Row" >
        </div>
    </div>

    {$INVENTORY_CONTENT}

</div>
<hr style="margin-right:13%">
<div>
    <div style="display: flex;margin-left: -50px;margin-top:10px;text-align:center;">
        <div class="col-md-2 book_column" style="text-align:right;">
            <b>Total Quantity:</b>
        </div>
        <div class="col-md-1 book_column" style="text-align:right;">
            <b><label id="book_total_quantity"></label></b>
        </div>
        <div class="col-md-2 book_column" style="text-align:right;">
            <b>Total Amount:</b>
        </div>
        <div class="col-md-2 book_column" style="text-align:right;">
            <b><label id="book_total_amount"></label></b>
        </div>
        <div class="col-md-2 book_column">
        </div>
    </div>
</div>