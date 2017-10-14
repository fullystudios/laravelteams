@foreach ($teams as $team)
 <ul>
     <li><a href="{{$team->path()}}">{{$team->name}}</a></li>
 </ul>
@endforeach