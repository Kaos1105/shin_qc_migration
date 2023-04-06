
<div class="w-100" style="display: flex; padding: 0px 15px 5px 0px">
    @if($paginate->total() == 1)
        <div>先頭へ</div>
        <div style="margin-right: 5px;margin-left: 5px">|</div>
        <div><< 前の20件へ</div>
        <div style="margin-right: 5px;margin-left: 5px">|</div>
        <div>次の20件へ >></div>
        <div style="margin-right: 5px;margin-left: 5px">|</div>
        <div>末尾へ</div>
    @else
        <?php
        $url_self = url()->current();
        $current_page = 1;
        $param = '';
        if(isset($_GET['page'])){
            $current_page = (int) $_GET['page'];
        }        
        if(isset($_GET['year'])){
            $param .= '&year='.$_GET['year'];
        }
        if(isset($_GET['sort'])){
            $param .= '&sort='.$_GET['sort'];
        }
        if(isset($_GET['sortType'])){
            $param .= '&sortType='.$_GET['sortType'];
        }
        if(isset($_GET['filter'])){
            $param .= '&filter='.$_GET['filter'];
        }
        $url_first = $url_self.'?page=1'.$param;
        $url_pre = $url_self.'?page='.($current_page - 1).$param;
        $url_next = $url_self.'?page='.($current_page + 1).$param;
        $url_last = $url_self.'?page='.$paginate->lastPage().$param;
        ?>
        @if($paginate->currentPage() == 1)
            <div>先頭へ</div>
        @else
            <a href="{{ URL::to($url_first) }}">先頭へ</a>
        @endif

        <div style="margin-right: 5px;margin-left: 5px">|</div>

        @if($paginate->currentPage() == 1)
            <div><< 前の20件へ</div>
        @else
            <a href="{{ URL::to($url_pre) }}"><< 前の20件へ</a>
        @endif

        <div style="margin-right: 5px;margin-left: 5px">|</div>

        @if($paginate->currentPage() == $paginate->lastPage())
            <div>次の20件へ >></div>
        @else
            <a href="{{ URL::to($url_next) }}">次の20件へ >></a>
        @endif

        <div style="margin-right: 5px;margin-left: 5px">|</div>

        @if($paginate->currentPage() == $paginate->lastPage())
            <div>末尾へ</div>
        @else
            <a href="{{ URL::to($url_last) }}">末尾へ</a>
        @endif
    @endif
</div>