export const currencyFormat = (num) => {
    return Number(num).toLocaleString("en-US", {
        style: "currency",
        currency: "PHP",
    });
};
