<label for='sector_id'>* Sector/Industry</label>
<select id='sector_id' name='sector_id'>
    <option value='0'>Choose</option>
    @foreach($sectorsForDropdown as $sector_id => $sectorType)
        <option value='{{ $sector_id }}'>
            {{ $sectorType }}
        </option>
    @endforeach
</select>

<label>Tags</label>
<ul id='tags'>
    @foreach($tagsForCheckboxes as $id => $name)
        <li><input
            type='checkbox'
            value='{{ $id }}'
            id='tag_{{ $id }}'
            name='tags[]'
        >&nbsp;
        <label for='tag_{{ $id }}'>{{ $name }}</label></li>
    @endforeach
</ul>


<label for='exchange'>* Stock Exchange</label>
<select name='exchange'>
    <option value='' {{ ($exchange == 'none') ? 'SELECTED' : '' }}>None</option>
    <option value="djia" {{ ($exchange == 'djia') ? 'SELECTED' : '' }}>djia</option>
    <option value="nasdaq" {{ ($exchange == 'nasdaq') ? 'SELECTED' : '' }}>nasdaq</option>
    <option value="nyse" {{ ($exchange == 'nyse') ? 'SELECTED' : '' }}>nyse</option>
    <option value="sp400" {{ ($exchange == 'sp400') ? 'SELECTED' : '' }}>sp400</option>
    <option value="sp500" {{ ($exchange == 'sp500') ? 'SELECTED' : '' }}>sp500</option>
    <option value="sp600" {{ ($exchange == 'sp600') ? 'SELECTED' : '' }}>sp600</option>
    <option value="nyse_mkt" {{ ($exchange == 'nyse_mkt') ? 'SELECTED' : '' }}>nyse_mkt</option>
</select>

<label for='dividend'>* Dividend</label>
<select name='dividend'>
    <option value='' {{ ($dividend == 'none') ? 'SELECTED' : '' }}>None</option>
    <option value="monthly" {{ ($dividend == 'monthly') ? 'SELECTED' : '' }}>monthly</option>
    <option value="quarterly" {{ ($dividend == 'quarterly') ? 'SELECTED' : '' }}>quarterly</option>
    <option value="semi_annual" {{ ($dividend == 'semi_annual') ? 'SELECTED' : '' }}>semi-annual</option>
    <option value="annual" {{ ($dividend == 'annual') ? 'SELECTED' : '' }}>annual</option>
</select>

<label for='market'>* Market Capitalization</label>
<select name='market'>
    <option value='' {{ ($market == 'none') ? 'SELECTED' : '' }}>None</option>
    <option value="microcap" {{ ($market == 'microcap') ? 'SELECTED' : '' }}>Micro Cap</option>
    <option value="smallcap" {{ ($market == 'smallcap') ? 'SELECTED' : '' }}>Small Cap</option>
    <option value="midcap" {{ ($market == 'midcap') ? 'SELECTED' : '' }}>Mid Cap</option>
    <option value="largecap" {{ ($market == 'annual') ? 'SELECTED' : '' }}>Large Cap</option>
    <option value="megacap" {{ ($market == 'annual') ? 'SELECTED' : '' }}>Mega Cap</option>
</select>

<label for='sector'>* Sector/Industry</label>
<select name='sector'>
    <option value='' {{ ($sector == 'all') ? 'SELECTED' : '' }}>All</option>
    <option value="basic_materials" {{ ($sector == 'basic_materials') ? 'SELECTED' : '' }}>Basic Materials</option>
    <option value="capital_goods" {{ ($sector == 'capital_goods') ? 'SELECTED' : '' }}>Capital Goods</option>
    <option value="conglomerates" {{ ($sector == 'conglomerates') ? 'SELECTED' : '' }}>Conglomerates</option>
    <option value="consumer_cyclical" {{ ($sector == 'consumer_cyclical') ? 'SELECTED' : '' }}>Consumer Cyclical</option>
    <option value="consumer_non_cyclical" {{ ($sector == 'consumer_non_cyclical') ? 'SELECTED' : '' }}>Consumer Non-Cyclical</option>
    <option value="energy" {{ ($sector == 'energy') ? 'SELECTED' : '' }}>Energy</option>
    <option value="finance" {{ ($sector == 'finance') ? 'SELECTED' : '' }}>Finance</option>
    <option value="healthcare" {{ ($sector == 'healthcare') ? 'SELECTED' : '' }}>Healthcare</option>
    <option value="services" {{ ($sector == 'services') ? 'SELECTED' : '' }}>Services</option>
    <option value="technology" {{ ($sector == 'technology') ? 'SELECTED' : '' }}>Technology</option>
    <option value="transportation" {{ ($sector == 'transportation') ? 'SELECTED' : '' }}>Transportation</option>
    <option value="utilities" {{ ($sector == 'utilities') ? 'SELECTED' : '' }}>Utilities</option>
</select>

{{-- Extracted error code to its own view file --}}
@include('errors')
