<!-- Booking Start -->
<div class="container-fluid booking pb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="bg-white shadow" style="padding: 35px;">
            <form id="searchForm">


                <div class="row g-2">
                    <div class="col-md-10">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="date" id="date1" data-target-input="nearest">
                                    <input type="date" name="check_in" class="form-control datetimepicker-input"
                                        placeholder="Check in" data-target="#date1" data-toggle="datetimepicker" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="date" id="date2" data-target-input="nearest">
                                    <input type="date" name="check_out" class="form-control datetimepicker-input"
                                        placeholder="Check out" data-target="#date2" data-toggle="datetimepicker" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="beds" class="form-select">
                                    <option value="">Beds</option>
                                    <option value="1">Bed 1</option>
                                    <option value="2">Bed 2</option>
                                    <option value="3">Bed 3</option>
                                    <option value="4">Bed 4</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="bathrooms" class="form-select">
                                    <option value="">Bathrooms</option>
                                    <option value="1">Bathroom 1</option>
                                    <option value="2">Bathroom 2</option>
                                    <option value="3">Bathroom 3</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="room_name" class="form-control" placeholder="Room Name">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="room_id" class="form-control" placeholder="Room ID">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="searchBtn" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Booking End -->

<script>
document.getElementById("searchBtn").addEventListener("click", function() {
    var formData = new FormData(document.getElementById("searchForm"));
    var searchParams = new URLSearchParams(formData).toString();
    window.location.href = "?modules=Room&action=search&" + searchParams;
});
</script>