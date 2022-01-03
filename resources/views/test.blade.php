<!DOCTYPE html>
<html lang="en">
    <body>
    <form action="{{route('editNote')}}" method="POST">
        @csrf
        <input type="number" name="note_id">
        <input type="text" name="category">
        <button type="submit"> Submit </button>
    </form>
    </body>

</html>
