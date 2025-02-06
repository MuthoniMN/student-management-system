import PrimaryButton from "@/Components/PrimaryButton";
import { FaAnglesLeft, FaAnglesRight } from "react-icons/fa6";

export default function Pagination({ length, page, setPage, perPage } : {
    length: number,
    page: number,
    perPage: number,
    setPage: any
}){
    const totalPages = Math.ceil(length / perPage);

    const prevPage = () => {
        if((page - 1) <= 0) return;

        setPage(page-1);
    }

    const nextPage = () => {
        if((page + 1) > Math.ceil(length/10)) return;

        setPage(page+1);
    }


    const renderPageNumbers = () => {
        const pages = [];
        const maxVisiblePages = 5; // Number of visible page numbers around the current page
        const ellipsis = <span className="px-2">...</span>;

        // Always show the first page
        pages.push(
            <p
                key={1}
                onClick={() => setPage(1)}
                className={`hover:underline cursor-pointer ${page === 1 ? 'underline text-blue-700' : ''}`}
            >
                1
            </p>
        );

        // Show ellipsis if the current page is far from the start
        if (page > maxVisiblePages) {
            pages.push(ellipsis);
        }

        // Calculate the range of pages to show around the current page
        const startPage = Math.max(2, page - Math.floor(maxVisiblePages / 2));
        const endPage = Math.min(totalPages - 1, page + Math.floor(maxVisiblePages / 2));

        for (let i = startPage; i <= endPage; i++) {
            pages.push(
                <p
                    key={i}
                    onClick={() => setPage(i)}
                    className={`hover:underline cursor-pointer ${page === i ? 'underline text-blue-700' : ''}`}
                >
                    {i}
                </p>
            );
        }

        if (page < totalPages - Math.floor(maxVisiblePages / 2)) {
            pages.push(ellipsis);
        }

        // Always show the last page
        if (totalPages > 1) {
            pages.push(
                <p
                    key={totalPages}
                    onClick={() => setPage(totalPages)}
                    className={`hover:underline cursor-pointer ${page === totalPages ? 'underline text-blue-700' : ''}`}
                >
                    {totalPages}
                </p>
            );
        }

        return pages;
    };

    return (
            <div className="py-4 text-center flex justify-between items-center">
                <p className="font-light text-gray-500 italic">Showing page {page} of {Math.ceil(length/perPage)}</p>
                <div className="flex gap-4">
                    <PrimaryButton onClick={prevPage}>
                        <FaAnglesLeft />
                    </PrimaryButton>
                    {renderPageNumbers()}
                    <PrimaryButton onClick={nextPage}>
                        <FaAnglesRight />
                    </PrimaryButton>
                </div>
            </div>

    );
}
