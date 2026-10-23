<script setup>
import { usePage } from "@inertiajs/vue3";
import { watch } from "vue";
import Swal from "sweetalert2";
const page = usePage();
watch(
    () => page.props.flash?.error,
    (value) => {
        if (value) {
            Swal.fire({
                title: "Error!",
                text: value,
                icon: "error",
                timer: 1500,
                showConfirmButton: false,
            });
        }
    },
    { deep: true },
);

watch(
    () => page.props.flash?.success,
    (value) => {
        if (value) {
            if (value) {
                Swal.fire({
                    title: "Success!",
                    text: value,
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false,
                });
            }
        }
    },
    { deep: true },
);
</script>
<template></template>
