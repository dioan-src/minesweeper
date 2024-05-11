<?php
class GridComponent
{
    public static function generateGrid($rows, $columns): string
    {
        $grid = '<div class="container">';
        $grid .= '<table class="table is-bordered is-fullwidth">';
        $grid .= '<tbody>';
        
        for ($i = 0; $i < $rows; $i++) {
            $grid .= '<tr>';
            for ($j = 0; $j < $columns; $j++) {
                $grid .= '<td class="square-cell has-background-black has-text-centered">';
                $grid .= '<a href="#" class="has-text-text">'.(($i * $columns) + $j + 1).'</a>';
                $grid .= '</td>';
            }
            $grid .= '</tr>';
        }
        
        $grid .= '</tbody>';
        $grid .= '</table>';
        $grid .= '</div>';
        
        return $grid;
    }
}
?>
