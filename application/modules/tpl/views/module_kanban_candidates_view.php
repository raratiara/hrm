<style>
    /*.kanban-board {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        padding: 10px;
    }*/

    .kanban-board {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        overflow-y: hidden;   
        padding: 10px;
        width: 100%;
        box-sizing: border-box;
    }


    .kanban-column {
        background: #fafafa;
        border-radius: 8px;
        /*width: 300px;*/
        min-width: 250px;
        /*flex: 0 0 250px;
        display: flex;
        flex-direction: column;*/
        /*max-height: 80vh;*/
    }

    .kanban-header {
        font-weight: bold;
        padding: 10px;
        border-bottom: 2px solid #eee;
        border-radius: 8px 8px 0 0;
    }

    .kanban-items {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .kanban-card {
        background: #fff;
        border-radius: 6px;
        padding: 8px 10px;
        margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        font-size: 13px;
    }

    .kanban-card small {
        display: inline-block;
        margin-top: 6px;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 11px;
        color: #fff;
    }

    .status-belum { color: #b71c1c; }
    .status-proses { color: #f57c00; }
    .status-review { color: #6d4c41; }
    .status-tunda { color: #6a1b9a; }
    .status-selesai { color: #1b5e20; }

    /* Scrollbar kecil */
    .kanban-items::-webkit-scrollbar {
        width: 6px;
    }
    .kanban-items::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }
</style>

<!-- <div class="kanban-filters mb-3" style="display: flex; gap: 10px; align-items: center;">
    <select id="filterPosition" class="form-control" style="max-width: 200px;">
        <option value="">-- Filter by Position --</option>
        <option value="Engineering">Engineering</option>
        <option value="Finance">Finance</option>
        <option value="HR">HR</option>
        <option value="Marketing">Marketing</option>
        
    </select>

    <select id="filterDivisi" class="form-control" style="max-width: 200px;">
        <option value="">-- Filter by Divisi --</option>
        <option value="IT">IT</option>
        <option value="Operations">Operations</option>
        <option value="Sales">Sales</option>
        <option value="Support">Support</option>
        
    </select>
</div> -->

<br>








<!-- KANBAN -->
<div class="kanban-board" id="candidate-container">
    <!-- Kolom-kolom Kanban akan diisi lewat JS loadCardView() -->
</div>
