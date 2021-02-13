@switch($status)
   @case('okay')
        <span>👍</span>
        @break
   @case('warn')
       <span>⚠️</span>
       @break
    @case('down')
        <span>💀</span>
        @break
    @default
        <span>🤷‍</span>
@endswitch
