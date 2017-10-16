<form method="POST" action="{{route('teams.store')}}">
    {{csrf_field()}}
    <label for="name">Name of team:</label>
    <input id="name" type="text" name="name" placeholder="name">
    <button type="submit">Create team</button>
</form>