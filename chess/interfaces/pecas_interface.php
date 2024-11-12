<?php
// chess/interfaces/pecas_interface.php

interface ChessPieceInterface {
    public function getType(): string;
    public function getColor(): string;
    public function setPosition(array $position): void;
    public function getPosition(): array;
    public function possibleMoves(array $position): array;
}
