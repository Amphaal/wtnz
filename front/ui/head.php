<div id='head'>
    <div class='fctr'>WTNZ</div>
    <div id='banner'>
        <div id='banner-desc'>
            <span><?php echo $user_qs ?>'s Library</span>
        </div>
        <div id='banner-side'>
            <div style='font-size:0.7em'>last update : <?php echo date ("d/m/Y H:i", $latestUpdate)?></div>
            <div>
                <label for='showStats'>Statistics</label>
                <input id='showStats' type='checkbox' onclick="toggleStats(event)" style='margin:0;margin-left:0.25em;'>
            </div> 
        </div>
    </div>
</div>