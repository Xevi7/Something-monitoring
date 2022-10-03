function loginValidation(){
    var user = document.forms["loginForm"]["username"].value

    if(user == "") {
        document.getElementById("errUser").innerText = "Username cannot be empty"
    }
    else {
        document.forms["loginForm"].submit()
    }
}

function displayAddList(tipe) {
    if(tipe == "employee") {
        $("#body_employee").children("div:nth-child(3)").css("filter", "blur(2px)")
    }
    else if(tipe == "customer") {
        $("#body_customer").children("div:nth-child(3)").css("filter", "blur(2px)")
    }
    else if(tipe == "stock") {
        $("#body_stock").children("div:nth-child(3)").css("filter", "blur(2px)")
    }
    $(".addList").show()
}

function closeAddList(tipe) {
    if(tipe == "employee") {
        $("#body_employee").children("div:nth-child(3)").css("filter", "blur(0px)")
        document.getElementById("employeeForm").reset()
    }
    else if(tipe == "customer") {
        $("#body_customer").children("div:nth-child(3)").css("filter", "blur(0px)")
        document.getElementById("customerForm").reset()
    }
    else if(tipe == "stock" ) {
        $("#body_stock").children("div:nth-child(3)").css("filter", "blur(0px)")
        document.getElementById("stockForm").reset()
    }
    $(".addList").hide()
}

function closeEditList(tipe) {
    if(tipe == "employee") {
        document.forms["editEmployeeForm"]["editConfirmation"].value = "cancel"
        document.forms["editEmployeeForm"].submit()
    }
    else if(tipe == "customer") {
        document.forms["editCustomerForm"]["editConfirmation"].value = "cancel"
        document.forms["editCustomerForm"].submit()
    }
    else if(tipe == "stock") {
        document.forms["editStockForm"]["editConfirmation"].value = "cancel"
        document.forms["editStockForm"].submit()
    }
}

function editedList(tipe) {
    if(tipe == "employee") {
        $('#body_employee').children('div:nth-child(3)').css('filter', 'blur(0px)')
    }
    else if(tipe == "customer") {
        $('#body_customer').children('div:nth-child(3)').css('filter', 'blur(0px)')
    }
    else if(tipe == "stock") {
        $('#body_stock').children('div:nth-child(3)').css('filter', 'blur(0px)')
    }
    $(".editSuccess").hide()
    document.body.innerHTML = "<meta http-equiv='refresh' content='0'>";
}

function addedList(tipe) {
    $(".addSuccess").hide()
    if(tipe == "employee") {
        $("#body_employee").children("div:nth-child(3)").css("filter", "blur(0px)")
        document.getElementById("employeeForm").submit()
    }
    else if(tipe == "customer") {
        $("#body_customer").children("div:nth-child(3)").css("filter", "blur(0px)")
        document.getElementById("customerForm").submit()
    }
    else if(tipe == "stock") {
        $("#body_stock").children("div:nth-child(3)").css("filter", "blur(0px)")
        document.getElementById("stockForm").submit()
    }
}

function deletedList(tipe) {
    $(".deleteSuccess").hide()
    if(tipe == "employee") {
        $("#body_employee").children("div:nth-child(3)").css("filter", "blur(0px)")
    }
    else if(tipe == "customer") {
        $("#body_customer").children("div:nth-child(3)").css("filter", "blur(0px)")
    }
    else if(tipe == "stock") {
        $("#body_stock").children("div:nth-child(3)").css("filter", "blur(0px)")
    }
    document.body.innerHTML = "<meta http-equiv='refresh' content='0'>";
}

function restoredList(){
    $(".restoreSuccess").hide()
    $("#body_trashCan").children("div:nth-child(3)").css("filter", "blur(0px)")
    document.body.innerHTML = "<meta http-equiv='refresh' content='0'>";
}

function mtedList(){
    $(".emptySuccess").hide()
    $("#body_trashCan").children("div:nth-child(3)").css("filter", "blur(0px)")
    document.body.innerHTML = "<meta http-equiv='refresh' content='0'>";
}


// -----------employee-----------
function employeeValidation() {
    var nama = document.forms["employee"]["nama"].value
    var posisi = document.forms["employee"]["posisi"].value
    var tanggal = document.forms["employee"]["tanggal"].value
    var alamat = document.forms["employee"]["alamat"].value
    var today = new Date();
    var current_date = today.getFullYear() + "-" + String(today.getMonth() + 1).padStart(2, '0')
    + "-" + String(today.getDate()).padStart(2, '0')
    var valid = 1
    
    //nama
    if(nama == "") {
        document.getElementById("errName").innerText = "Name must be filled!"
        valid = 0
    }
    else if(nama.length < 2) {
        document.getElementById("errName").innerText = "Name must be more than 2 characters!"
        valid = 0
    }
    else if(/[^a-zA-Z ]/.test(nama)) {
        document.getElementById("errName").innerText = "Name must be from letter A - Z!"
        valid = 0
    }
    else {
        document.getElementById("errName").innerText = ""
    }
    
    //posisi
    if(posisi == "") {
        document.getElementById("errPos").innerText = "Position must be filled!"
        valid = 0
    }
    else if(posisi != "Associate" && posisi != "Manager" && posisi != 'Executive') {
        document.getElementById("errPos").innerText = "Position must be either Associate, Manager, or Executive!"
        valid = 0
    }
    else {
        document.getElementById("errPos").innerText = ""
    }
    
    //tanggal
    if(tanggal == "") {
        document.getElementById("errDate").innerText = "DOB must be filled!"
        valid = 0
    }
    else if(tanggal > current_date) {
        document.getElementById("errDate").innerText = "Invalid date!"
        valid = 0
    }
    else {
        document.getElementById("errDate").innerText = ""
    }
    
    //alamat
    if(alamat == "") {
        document.getElementById("errAddress").innerText = "Address must be filled!"
        valid = 0
    }
    else {
        document.getElementById("errAddress").innerText = ""
    }
    
    if(valid == 1) {
        $(".addList").hide()
        $(".addSuccess").show()
    }
    
}

function editEmployeevalidation() {
    var nama = document.forms["editEmployeeForm"]["editedName"].value
    var posisi = document.forms["editEmployeeForm"]["editedPosition"].value
    var alamat = document.forms["editEmployeeForm"]["editedAddress"].value
    var valid = 1

    //nama
    if(nama == "") {
        document.getElementById("errEditName").innerText = "Name must be filled!"
        valid = 0
    }
    else if(nama.length < 2) {
        document.getElementById("errEditName").innerText = "Name must be more than 2 characters!"
        valid = 0
    }
    else if(/[^a-zA-Z ]/.test(nama)) {
        document.getElementById("errEditName").innerText = "Name must be from letter A - Z!"
        valid = 0
    }
    else {
        document.getElementById("errEditName").innerText = ""
    }
    
    //posisi
    if(posisi == "") {
        document.getElementById("errEditPos").innerText = "Position must be filled!"
        valid = 0
    }
    else if(posisi != "Associate" && posisi != "Manager" && posisi != 'Executive') {
        document.getElementById("errEditPos").innerText = "Position must be either Associate, Manager, or Executive!"
        valid = 0
    }
    else {
        document.getElementById("errEditPos").innerText = ""
    }

    //alamat
    if(alamat == "") {
        document.getElementById("errEditAddress").innerText = "Address must be filled!"
        valid = 0
    }
    else {
        document.getElementById("errEditAddress").innerText = ""
    }
    
    if(valid == 1) {
        $(".editList").hide()
        document.forms["editEmployeeForm"].submit()
    }
}

// -----------customer-----------
function customerValidation() {
    var nama = document.forms["customer"]["namaCust"].value
    var membership = document.forms["customer"]["membership"].value
    var tanggal = document.forms["customer"]["tanggalCust"].value
    var alamat = document.forms["customer"]["alamatCust"].value
    var today = new Date();
    var current_date = today.getFullYear() + "-" + String(today.getMonth() + 1).padStart(2, '0')
    + "-" + String(today.getDate()).padStart(2, '0')
    var valid = 1
    
    //nama
    if(nama == "") {
        document.getElementById("errName").innerText = "Name must be filled!"
        valid = 0
    }
    else if(nama.length < 2) {
        document.getElementById("errName").innerText = "Name must be more than 2 characters!"
        valid = 0
    }
    else if(/[^a-zA-Z ]/.test(nama)) {
        document.getElementById("errName").innerText = "Name must be from letter A - Z!"
        valid = 0
    }
    else {
        document.getElementById("errName").innerText = ""
    }
    
    //membership
    if(membership == "") {
        document.getElementById("errMem").innerText = "Position must be filled!"
        valid = 0
    }
    else if(membership != "Bronze" && membership != "Silver" && membership != 'Gold') {
        document.getElementById("errMem").innerText = "Position must be either Bronze, Silver, or Gold!"
        valid = 0
    }
    else {
        document.getElementById("errMem").innerText = ""
    }
    
    //tanggal
    if(tanggal == "") {
        document.getElementById("errDate").innerText = "DOB must be filled!"
        valid = 0
    }
    else if(tanggal > current_date) {
        document.getElementById("errDate").innerText = "Invalid date!"
        valid = 0
    }
    else {
        document.getElementById("errDate").innerText = ""
    }
    
    //alamat
    if(alamat == "") {
        document.getElementById("errAddress").innerText = "Address must be filled!"
        valid = 0
    }
    else {
        document.getElementById("errAddress").innerText = ""
    }
    
    if(valid == 1) {
        $(".addList").hide()
        $(".addSuccess").show()
    }
    
}

function editCustomervalidation() {
    var nama = document.forms["editCustomerForm"]["editedName"].value
    var membership = document.forms["editCustomerForm"]["editedMembership"].value
    var alamat = document.forms["editCustomerForm"]["editedAddress"].value
    var valid = 1

    //nama
    if(nama == "") {
        document.getElementById("errEditName").innerText = "Name must be filled!"
        valid = 0
    }
    else if(nama.length < 2) {
        document.getElementById("errEditName").innerText = "Name must be more than 2 characters!"
        valid = 0
    }
    else if(/[^a-zA-Z ]/.test(nama)) {
        document.getElementById("errEditName").innerText = "Name must be from letter A - Z!"
        valid = 0
    }
    else {
        document.getElementById("errEditName").innerText = ""
    }
    
    //membership
    if(membership == "") {
        document.getElementById("errEditMem").innerText = "Membership must be filled!"
        valid = 0
    }
    else if(membership != "Bronze" && membership != "Silver" && membership != 'Gold') {
        document.getElementById("errEditMem").innerText = "Membership must be either Bronze, Silver, or Gold!"
        valid = 0
    }
    else {
        document.getElementById("errEditMem").innerText = ""
    }

    //alamat
    if(alamat == "") {
        document.getElementById("errEditAddress").innerText = "Address must be filled!"
        valid = 0
    }
    else {
        document.getElementById("errEditAddress").innerText = ""
    }
    
    if(valid == 1) {
        $(".editList").hide()
        document.forms["editCustomerForm"].submit()
    }
}


// -----------stock-----------
function stockValidation() {
    var nama = document.forms["stock"]["namaStock"].value
    var gudang = document.forms["stock"]["gudang"].value
    var jumlah = document.forms["stock"]["jumlahStock"].value
    var valid = 1
    
    //nama
    if(nama == "") {
        document.getElementById("errName").innerText = "Product name must be filled!"
        valid = 0
    }
    else if(nama.length < 2) {
        document.getElementById("errName").innerText = "Product name must be more than 2 characters!"
        valid = 0
    }
    else {
        document.getElementById("errName").innerText = ""
    }
    
    //gudang
    if(gudang == "") {
        document.getElementById("errAddress").innerText = "Warehouse address must be filled!"
        valid = 0
    }
    else {
        document.getElementById("errAddress").innerText = ""
    }
    
    //jumlah
    if(jumlah == "") {
        document.getElementById("errQuantity").innerText = "product quantity must be filled!"
        valid = 0
    }
    else if(jumlah < 0) {
        document.getElementById("errQuantity").innerText = "product quantity cannot be less than zero"
        valid = 0
    }
    else {
        document.getElementById("errQuantity").innerText = ""
    }
    
    if(valid == 1) {
        $(".addList").hide()
        $(".addSuccess").show()
    }
    
}

function editStockvalidation() {
    var nama = document.forms["editStockForm"]["editedName"].value
    var gudang = document.forms["editStockForm"]["editedAddress"].value
    var jumlah = document.forms["editStockForm"]["editedQuantity"].value
    var valid = 1

    //nama
    if(nama == "") {
        document.getElementById("errEditName").innerText = "Product name must be filled!"
        valid = 0
    }
    else if(nama.length < 2) {
        document.getElementById("errEditName").innerText = "Product name must be more than 2 characters!"
        valid = 0
    }
    else {
        document.getElementById("errEditName").innerText = ""
    }
    
    //gudang
    if(gudang == "") {
        document.getElementById("errEditAddress").innerText = "Warehouse address must be filled!"
        valid = 0
    }
    else {
        document.getElementById("errEditAddress").innerText = ""
    }
    
    //jumlah
    if(jumlah == "") {
        document.getElementById("errEditQuantity").innerText = "product quantity must be filled!"
        valid = 0
    }
    else {
        document.getElementById("errEditQuantity").innerText = ""
    }
    
    if(valid == 1) {
        $(".editList").hide()
        document.forms["editStockForm"].submit()
    }
}


// -----------audit log-----------
$(".arrowDetail").click(function(){
    $(this).children("img").toggleClass("down")
    $(this).parent().next().slideToggle("fast")
})