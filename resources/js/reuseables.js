export const currencyFormat = (num) => {
    return Number(num).toLocaleString("en-US", {
        style: "currency",
        currency: "PHP",
    });
};

export const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("en-US", options);
};
