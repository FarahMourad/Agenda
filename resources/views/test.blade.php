<!DOCTYPE html>
<html lang="en">
    <body>
    <form action="{{route('setContent')}}" method="POST">
        @csrf
        <input type="text" name="pageContent">
        <input type="number" name="page_no">
        <input type="number" name="bookmarked">
        <button type="submit"> Submit </button>
    </form>

    </body>

</html>
