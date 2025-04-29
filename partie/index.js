class BatailleNavale {
    constructor(mode, player1Name, player2Name) {
        this.mode = mode;
        this.player1Name = player1Name;
        this.player2Name = player2Name;
        this.turn = 1;
        
        this.player1Grid = document.getElementById('player1-grid');
        this.player2Grid = document.getElementById('player2-grid');
        this.messageDiv = document.getElementById('message');
        this.menuScreen = document.getElementById('menu-screen');
        this.gameScreen = document.getElementById('game-screen');
        this.backToMenuBtn = document.getElementById('back-to-menu');
        
        this.player1Ships = [];
        this.player2Ships = [];
        this.computerShips = [];
        
        this.initGame();
        this.generate_occupied_grids();
        this.computerAttack();
        this.count();
    }

    initGame() {
        this.clearGrids();
        this.createGrid(this.player1Grid, true);
        this.createGrid(this.player2Grid, false);
        this.tryPlaceShip(this.computerShips, this.player1Grid);
        this.tryPlaceShip(this.player2Ships, this.player2Grid);
    }

    clearGrids() {
        this.player1Grid.innerHTML = '';
        this.player2Grid.innerHTML = '';
        this.player1Ships = [];
        this.player2Ships = [];
        this.messageDiv.textContent = '';
    }

    createGrid(gridElement, isPlayer) {
        for (let i = 0; i < 100; i++) {
            const cell = document.createElement('div');
            cell.classList.add('cell');
            cell.dataset.index = i;
            if (!isPlayer)
            {
                cell.addEventListener('click', () => this.playerAttack(cell, this.player2Grid));
            }
            gridElement.appendChild(cell);
        }
    }

    randomize (array1)
    {
        if (array1 != undefined)
        {
            let index;
            let array2 = [];

            do
            {
                index = Math.floor(Math.random() * array1.length);
                array2.push(array1[index]);
                array1.splice(index, 1);
            } while (array1.length != 0)
            
            return array2;
        }
    }

    generate_occupied_grids()
    {
        let ships = [];

        let occupied_grids = [];

        let grid = {
            'x': 0,
            'y': 0
        };

        ships = this.randomize([[2, "green"], [3, "blue"], [3, "cyan"], [4, "purple"], [5, "red"]]);

        let i;
        for (let ship = 0; ship < ships.length; ship++) {
            let invalid;
            if (occupied_grids.length != 0) {
                do {
                    grid.x = Math.floor(Math.random() * 10);
                    grid.y = Math.floor(Math.random() * 10);
                    invalid = true;
                    for (let occupied_grid = 0; occupied_grid < occupied_grids.length; occupied_grid++) {
                        if (occupied_grids[occupied_grid] != undefined) {
                            for (let occupied_grid_y = 0; occupied_grid_y < occupied_grids[occupied_grid].length; occupied_grid_y++) {
                                if (occupied_grids[occupied_grid][occupied_grid_y] == grid.y && occupied_grid == grid.x) {
                                    invalid = false;
                                    break;
                                }
                            }
                        }
                    }
                } while (!invalid)
            } else {
                grid.x = Math.floor(Math.random() * 10);
                grid.y = Math.floor(Math.random() * 10);
            }

            let valid_directions = this.randomize(["up", "right", "down", "left"]);

            if (grid.y + 1 < ships[ship][0]) {
                valid_directions.splice(valid_directions.indexOf("up"), 1);
            }
            if (10 - grid.x < ships[ship][0]) {
                valid_directions.splice(valid_directions.indexOf("right"), 1);
            }
            if (10 - grid.y < ships[ship][0]) {
                valid_directions.splice(valid_directions.indexOf("down"), 1);
            }
            if (grid.x + 1 < ships[ship][0]) {
                valid_directions.splice(valid_directions.indexOf("left"), 1);
            }

            for (let direction = 0; direction < valid_directions.length; direction++) {
                let invalid2 = false;
                switch (valid_directions[direction]) {
                    case "up":
                        for (i = 1; i < ships[ship][0]; i++) {
                            for (let occupied_grid = 0; occupied_grid < occupied_grids.length; occupied_grid++) {
                                if (occupied_grids[occupied_grid] != undefined) {
                                    for (let occupied_grid_y = 0; occupied_grid_y < occupied_grids[occupied_grid].length; occupied_grid_y++) {
                                        if (occupied_grid == grid.x && occupied_grids[occupied_grid][occupied_grid_y] == grid.y - i) {
                                            valid_directions.splice(valid_directions.indexOf("up"), 1);
                                            invalid2 = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (i == ships[ship][0] && !invalid2) {
                            if (occupied_grids[grid.x] == undefined) {
                                occupied_grids[grid.x] = [];
                            }
                            for (let j = 0; j < ships[ship][0]; j++) {
                                occupied_grids[grid.x].push(grid.y - j);
                            }
                            direction = valid_directions.length;
                            break;
                        }
                        break;
                    case "right":
                        for (i = 1; i < ships[ship][0]; i++) {
                            for (let occupied_grid = 0; occupied_grid < occupied_grids.length; occupied_grid++) {
                                if (occupied_grids[occupied_grid] != undefined) {
                                    for (let occupied_grid_y = 0; occupied_grid_y < occupied_grids[occupied_grid].length; occupied_grid_y++) {
                                        if (occupied_grid == grid.x + i && occupied_grids[occupied_grid][occupied_grid_y] == grid.y) {
                                            valid_directions.splice(valid_directions.indexOf("right"), 1);
                                            invalid2 = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (i == ships[ship][0] && !invalid2) {
                            for (let j = 0; j < ships[ship][0]; j++) {
                                if (occupied_grids[grid.x + j] == undefined) {
                                    occupied_grids[grid.x + j] = [];
                                }
                                occupied_grids[grid.x + j].push(grid.y);
                            }
                            direction = valid_directions.length;
                            break;
                        }
                        break;
                    case "down":
                        for (i = 1; i < ships[ship][0]; i++) {
                            for (let occupied_grid = 0; occupied_grid < occupied_grids.length; occupied_grid++) {
                                if (occupied_grids[occupied_grid] != undefined) {
                                    for (let occupied_grid_y = 0; occupied_grid_y < occupied_grids[occupied_grid].length; occupied_grid_y++) {
                                        if (occupied_grid == grid.x && occupied_grids[occupied_grid][occupied_grid_y] == grid.y + i) {
                                            valid_directions.splice(valid_directions.indexOf("down"), 1);
                                            invalid2 = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (i == ships[ship][0] && !invalid2) {
                            if (occupied_grids[grid.x] == undefined) {
                                occupied_grids[grid.x] = [];
                            }
                            for (let j = 0; j < ships[ship][0]; j++) {
                                occupied_grids[grid.x].push(grid.y + j);
                            }
                            direction = valid_directions.length;
                            break;
                        }
                        break;
                    case "left":
                        for (i = 1; i < ships[ship][0]; i++) {
                            for (let occupied_grid = 0; occupied_grid < occupied_grids.length; occupied_grid++) {
                                if (occupied_grids[occupied_grid] != undefined) {
                                    for (let occupied_grid_y = 0; occupied_grid_y < occupied_grids[occupied_grid].length; occupied_grid_y++) {
                                        if (occupied_grid == grid.x - i && occupied_grids[occupied_grid][occupied_grid_y] == grid.y) {
                                            valid_directions.splice(valid_directions.indexOf("left"), 1);
                                            invalid2 = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (i == ships[ship][0] && !invalid2) {
                            for (let j = 0; j < ships[ship][0]; j++) {
                                if (occupied_grids[grid.x - j] == undefined) {
                                    occupied_grids[grid.x - j] = [];
                                }
                                occupied_grids[grid.x - j].push(grid.y);
                            }
                            direction = valid_directions.length;
                            break;
                        }
                        break;
                }
            }
        }
        return occupied_grids;
    }


    tryPlaceShip(shipArray, gridElement) {
        let c = 0;
        let ship = [];

        do{
        ship = this.generate_occupied_grids();
        c = 0;
        for (let i = 0; i < ship.length; i++)
        {
            if (ship[i] != undefined)
            for (let j = 0; j < ship[i].length; j++)
            {
                c++;
            }
        }
        } while (c != 17)

        for (let i = 0; i < ship.length; i++)
        {
            if (ship[i] != undefined)
            {
                for (let j = 0; j < ship[i].length; j++)
                {
                    const cell = gridElement.querySelector(`[data-index="${i * 10 + ship[i][j]}"]`);
                    if (gridElement == this.player1Grid)
                    {
                        cell.classList.add('ship');
                    }
                    else
                    {
                        cell.classList.add('invisible');
                    }
                }
            }
        }
        shipArray.push(ship);
        return true;
    }

    sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    playerAttack(cell, gridElement) {
        if (this.turn == 1)
        {
            for (let i = 0; i < 100; i++)
            {
                const cell = this.player2Grid.querySelector(`[data-index="${i}"]`);
                cell.classList.add('focus');
            }   
            if (cell.classList.contains('invisible')) {
                cell.classList.add('ship');
                cell.classList.add('hit');
                for (let i = 0; i < 100; i++)
                {
                    const cell = this.player2Grid.querySelector(`[data-index="${i}"]`);
                    cell.classList.remove('focus');
                }   
                this.checkGameStatus(this.player2Grid);
            } else {
                cell.classList.add('miss');
                this.turn = 0;
                this.computerAttack()
            }
        }
    }

    async computerAttack ()
    {
        await this.sleep(1000);
        do
        {
            let hitCell;
            let cell;
            do {
                hitCell = 10 * Math.floor(Math.random() * 10) + Math.floor(Math.random() * 10);
                cell = this.player1Grid.querySelector(`[data-index="${hitCell}"]`);
            } while (cell.classList.contains('hit') || cell.classList.contains('miss'));
            if (cell.classList.contains('ship')) {
                cell.classList.add('hit');
                await this.sleep(1500);
                this.checkGameStatus(this.player1Grid);
            } else {
                cell.classList.add('miss');
                this.turn = 1;                        
            }
            for (let i = 0; i < 100; i++)
            {
                const cell = this.player2Grid.querySelector(`[data-index="${i}"]`);
                cell.classList.remove('focus');
            }           
        } while (this.turn == 0)
    }

    count(arr)
    {
        if(arr != undefined)
        {
            let counter = 0;
            for (let i = 0; i < arr.length; i++)
            {
                for (let j = 0; j < arr[i].length; j++)
                {
                    counter++;
                }
            }
            return counter;
        }
    }


    checkGameStatus(targetGrid) {
        const hitShips = targetGrid.querySelectorAll('.ship.hit');
        const totalShipCells = targetGrid === this.player1Grid 
            ? this.count(this.player1Ships.flat()) 
            : this.count(this.computerShips.flat());

        if (hitShips.length === totalShipCells) {
            const winnerName = targetGrid === this.player2Grid ? "player" : "computer";
            if (winnerName == "player")
            {
                this.messageDiv.textContent = `joueur a gagné !`;
            }
            else
            {
                this.messageDiv.textContent = `ordinateur a gagné !`;

            }
            for (let i = 0; i < 100; i++) {
                let cell = this.player2Grid.querySelector(`[data-index="${i}"]`);
                cell.classList.add('end');
            }
            this.turn = 1;
        }    
    }
}

