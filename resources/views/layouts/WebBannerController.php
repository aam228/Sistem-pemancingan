@extends('app.structure')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <h1 class="text-center mb-4">Converter</h1>

    <form action="{{ route('converter.run') }}" method="POST">
        @csrf
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="sourcePath" class="form-label">Source Path</label>
                    <input type="text" name="sourcePath" id="sourcePath" class="form-control" placeholder="/home/user/images">
                </div>
    
                <div class="mb-3">
                    <label for="limit" class="form-label">Jumlah file yang di-convert</label>
                    <input type="number" name="limit" id="limit" class="form-control" placeholder="0" min="1">
                </div>
    
                <div class="mb-3">
                    <label for="delay" class="form-label">Delay (microseconds)</label>
                    <input type="number" name="delay" id="delay" class="form-control" value="1000" min="0">
                </div>
    
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Convert</button>
                </div>
            </div>
        </div>
    </form>
</div>

@if(session('success'))
<div id="customAlert" 
     style="position:fixed; top:20%; left:50%; transform:translateX(-50%);
            background:#d4edda; color:#155724;
            padding:10px 20px; border:1px solid #c3e6cb; border-radius:5px;
            display:none; z-index:9999; cursor:pointer;">
    ✔ {{ session('success') }}
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var alertBox = document.getElementById("customAlert");

        alertBox.style.display = "block";

        setTimeout(function(){ alertBox.style.display = "none"; }, 3000);

        alertBox.addEventListener("click", function() {
            alertBox.style.display = "none";
        });
    });
</script>
@endif
